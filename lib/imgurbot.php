<?php

include_once(__DIR__ . '/Net_SmartIRC-1.0.0/SmartIRC.php');

class ImgurBot {
  var $imgUrl = 'http://imgur.com';
  var $nick;
  var $target;
  var $host;

  function __construct($conf) {
    $this->nick = $conf->nick;
    $this->target = $conf->target;
    $this->host = $conf->host;
  }

  function quit(&$irc, &$data) {
    if($data->nick == $nick) $irc->disconnect();
  }

  function randomTop(&$irc, $format = 'json') {
    $images = $this->getPageJson('/gallery/top/all', $format);
    $random = $images->data[rand(0, count($images->data) - 1)];

    $irc->message(SMARTIRC_TYPE_CHANNEL, $this->target, $this->imgUrl . '/gallery/' . $random->hash);
  }

  function nickFinder(&$irc, &$data) {
    if(preg_match("/^$this->host/", $data->host) == 1) {
      if($data->rawmessageex[1] == 'JOIN') {
        $this->target = $data->nick;
      } else {
        $this->target = $data->message;
      }

      $irc->message(SMARTIRC_TYPE_CHANNEL, $this->target, "You can run but you can't hide!");
    }
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