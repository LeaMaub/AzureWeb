<?php
namespace AzurWeb;

class Project {
    public $url;
    public $image;
    public $altText;
    public $title;

    public function __construct($url, $image, $altText, $title) {
        $this->url = $url;
        $this->image = $image;
        $this->altText = $altText;
        $this->title = $title;
    }

    public function render() {
        return '<a href="' . $this->url . '" class="project" target="_blank">' .
                    '<img src="/public/images/' . $this->image . '" alt="' . $this->altText . '">' .
                    '<div class="overlay">' .
                    '<span class="project-title">' . $this->title . '</span>' .
                    '</div>' .
                '</a>';
    }    
}

class Portfolio {
    private $projects = [];

    public function addProject(Project $project) {
        $this->projects[] = $project;
    }

    public function render() {
        $html = '<div class="portfolio__projects">';
        foreach ($this->projects as $project) {
            $html .= $project->render();
        }
        $html .= '</div>';
        return $html;
    }
}

?>
