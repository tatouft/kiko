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
    $formLink = "https://forms.gle/brMuGB6NZCVyminC6";
    $title = new Text("Réservation", "Registration");
    $desc = new Text("Les réservations", "Registration");
?>
<?php include($_SERVER['DOCUMENT_ROOT'] . "/redirection.php"); ?>