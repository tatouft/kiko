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
    $state = SeminarStatus::Closed;
    $formLink = "https://forms.gle/brMuGB6NZCVyminC6";
    $title = new Text("Réservation", "Registration");
    $desc = new Text("Les réservations", "Registration");
    $msg = new Text("Désolé nous avons atteint le nombre maximum de repas. Mais pas de panique, vous êtes toujours les bienvenu pour venir prendre un verre avec nous", "Sorry, we have reached the maximum number of meals. But don’t worry, you are still very welcome to come and have a drink with us")
?>
<?php include($_SERVER['DOCUMENT_ROOT'] . "/redirection.php"); ?>