<?php

include_once(__DIR__ . '/lib/imgurbot.php');

$conf = json_decode(implode("\n", file(__DIR__ . '/conf/settings.conf')));

$bot = &new ImgurBot($conf);
$irc = &new Net_SmartIRC();
if($conf->debug) $irc->setDebug(SMARTIRC_DEBUG_ALL);
$irc->registerTimehandler($conf->timing, $bot, 'randomTop');
if($conf->ssl) {
  $irc->setUseSockets(FALSE);
  $irc->connect('ssl://' . $conf->server, $conf->port);
} else {
  $irc->setUseSockets(TRUE);
  $irc->connect($conf->server, $conf->port);
}

$irc->login($conf->nick, 'ImgurBot', 0, $conf->nick);
$irc->listen();
$irc->disconnect();
