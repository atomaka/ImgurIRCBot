<?php

include_once(__DIR__ . '/../Net_SmartIRC-1.0.0/SmartIRC.php');

class ImgurBot {
  var $imgUrl = 'http://imgur.com';
  var $nick;

  function __construct($conf) {
    $this->nick = $conf->nick;
    $this->target = $conf->target;
  }

  function quit(&$irc, &$data) {
    if($data->nick == $nick) $irc->disconnect();
  }

  function randomTop(&$irc, $format = 'json') {
    $images = $this->getPageJson('/gallery/top/all', $format);
    $random = $images->data[rand(0, count($images->data) - 1)];

    $irc->message(SMARTIRC_TYPE_CHANNEL, $this->target, $this->imgUrl . '/gallery/' . $random->hash);
  }

  private
    function getPageJson($page, $format = 'json') {
      $contents = file($this->imgUrl . '/' . $page . '.' . $format);
      if($format == 'json') {
        return json_decode(implode("\n", $contents));
      } else {
        return false;
      }
    }
}