<?php

class Transaction {
    private $senderId;
    private $receiverId;
    private $amount;
    private $reason;

    public function setSenderId($senderId) {
        $this->senderId = $senderId;
    }

    public function setReceiverId($receiverId) {
        $this->receiverId = $receiverId;
    }

    public function setAmount($amount) {
        $amount = (float) $amount;
        if ($amount < 1) {
            throw new Exception("Het bedrag moet minstens 1 zijn.");
        }
        $this->amount = $amount;
    }

    public function setReason($reason) {
        $reason = trim($reason);
        $this->reason = $reason !== '' ? $reason : null;
    }

    public function send($pdo) {
        if ($this->senderId === $this->receiverId) {
            throw new Exception("Je kan geen geld naar jezelf sturen.");
        }

        $stmt = $pdo->prepare("SELECT balance FROM users WHERE id = :id");
        $stmt->execute(['id' => $this->senderId]);
        $sender = $stmt->fetch();

        if (!$sender) {
            throw new Exception("Afzender niet gevonden.");
        }

        if ($this->amount > $sender['balance']) {
            throw new Exception("Onvoldoende saldo voor deze transfer.");
        }

        $stmt = $pdo->prepare("SELECT id FROM users WHERE id = :id");
        $stmt->execute(['id' => $this->receiverId]);
        if (!$stmt->fetch()) {
            throw new Exception("Ontvanger niet gevonden.");
        }

        $pdo->beginTransaction();

        try {
            $stmt = $pdo->prepare("UPDATE users SET balance = balance - :amount WHERE id = :id");
            $stmt->execute(['amount' => $this->amount, 'id' => $this->senderId]);

            $stmt = $pdo->prepare("UPDATE users SET balance = balance + :amount WHERE id = :id");
            $stmt->execute(['amount' => $this->amount, 'id' => $this->receiverId]);

            $stmt = $pdo->prepare("INSERT INTO transactions (sender_id, receiver_id, amount, reason) VALUES (:sender_id, :receiver_id, :amount, :reason)");
            $stmt->execute([
                'sender_id' => $this->senderId,
                'receiver_id' => $this->receiverId,
                'amount' => $this->amount,
                'reason' => $this->reason
            ]);

            $pdo->commit();
            return true;
        } catch (Exception $e) {
            $pdo->rollBack();
            throw new Exception("De transfer is mislukt, probeer opnieuw.");
        }
    }
}