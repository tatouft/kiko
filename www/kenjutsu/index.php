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
    $state = SeminarStatus::closed;
    $formLink = "https://forms.gle/M9WCHkW9szyoav4H6";
    $title = new Text("Inscription", "Registration");
    $desc = new Text("Les inscriptions au séminaire de kenjutsu", "Registration for the kenjutsu seminar");
?>
<?php include($_SERVER['DOCUMENT_ROOT'] . "/redirection.php"); ?>
