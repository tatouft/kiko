<?php
    class SeminarStatus {
        const Closed = 'closed';
        const Open = 'open';
        const AlmostOpen = 'almost_open';
    }
    class Text {
        public string $fr;
        public string $en;

        public function __construct(string $fr, string $en) {
            $this->fr = $fr;
            $this->en = $en;
        }
    }
    $state = SeminarStatus::Open;
    $formLink = "https://docs.google.com/forms/d/e/1FAIpQLSfgmjCDk0n3kNYWGzu4ZBkMpLS5T-RccSBpcxAe6JrY7SNNmA/viewform";
    $title = new Text("Commande", "Order");
    $desc = new Text("Les commandes", "Orders");
?>
<?php include($_SERVER['DOCUMENT_ROOT'] . "/redirection.php"); ?>
