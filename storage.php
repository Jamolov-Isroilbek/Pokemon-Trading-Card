<?php

class Storage {
    private $filePath;

    public function __construct($filePath) {
        $this->filePath = $filePath;
    }

    public function getAllCards() {
        if (file_exists($this->filePath)) {
            return json_decode(file_get_contents($this->filePath), true);
        }
        return [];
    }

    public function getCardByName($name) {
        $cards = $this->getAllCards();
        foreach ($cards as $card) {
            if ($card['name'] === $name) {
                return $card;
            }
        }
        return null;
    }

    public function updateCard($updatedCard) {
        $cards = $this->getAllCards();
        foreach ($cards as $i => $card) {
            if ($card['name'] === $updatedCard['name']) {
                $cards[$i] = $updatedCard;
                break;
            }
        }
        file_put_contents($this->filePath, json_encode($cards));
    }
}
