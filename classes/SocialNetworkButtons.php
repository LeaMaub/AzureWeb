<?php
namespace AzurWeb;

class SocialLink {
    private $url;
    private $faClass;
    private $screenReaderText;

    public function __construct($url, $faClass, $screenReaderText) {
        $this->url = $url;
        $this->faClass = $faClass;
        $this->screenReaderText = $screenReaderText;
    }

    public function render() {
        echo "<a href=\"{$this->url}\" target=\"_blank\"><i class=\"{$this->faClass}\"></i><span class=\"sr-only\">{$this->screenReaderText}</span></a>";
    }
}

class SocialNetworkButtons {
    private $links = [];

    public function addLink($url, $faClass, $screenReaderText) {
        $this->links[] = new SocialLink($url, $faClass, $screenReaderText);
    }

    public function render() {
        echo '<div class="socialNetwork__buttons">';
        foreach ($this->links as $link) {
            $link->render();
        }
        echo '</div>';
    }
}
