<?php

class Video extends JBBCode\CodeDefinition {

    public function __construct() {
        parent::__construct();
        $this->setTagName("video");
    }

    public function asHtml(JBBCode\ElementNode $el) {
        $content = "";
        foreach ($el->getChildren() as $child)
            $content .= $child->getAsBBCode();

        $foundMatch = preg_match('/v=([A-z0-9=\-]+?)(&.*)?$/i', $content, $matches);
        if (!$foundMatch)
            return $el->getAsBBCode();
        else
            return "<lite-youtube loading=\"lazy\" videoid=\"{$matches[1]}\" style=\"width:\"640px\";height:\"390px\" background-image: url('https://i.ytimg.com/vi/{$matches[1]}/maxresdefault.jpg');\"></lite-youtube>";
    }

}
