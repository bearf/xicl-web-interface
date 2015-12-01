<?php

	session_start();

  //на всякий случай страхуемся и прописываем "пустые" значения

  if (!session_is_registered("authorized")) {
    session_register("authorized");
    $authorized = "0";
  }

  if (!session_is_registered("curlogin")) {
    session_register("curlogin");
    $curlogin = "";
  }

  if (!session_is_registered("curnickname")) {
    session_register("curnickname");
    $curlogin = "";
  }

  if (!session_is_registered("curuserid")) {
    session_register("curuserid");
    $curuserid = -1;
  }

  if (!session_is_registered("curpass")) {
    session_register("curpass");
    $curpass = "";
  }

  if (!session_is_registered("curcontest")) {
    session_register("curcontest");
    $curcontest = 1;
  }

  //защита от спама
  if (!session_is_registered("login_lastaccess")) {
    session_register("login_lastaccess");
    $login_lastaccess = 0;
  }

  if (!session_is_registered("submit_lastaccess")) {
    session_register("submit_lastaccess");
    $submit_lastaccess = 0;
  }

  if (!session_is_registered("register_lastaccess")) {
    session_register("register_lastaccess");
    $register_lastaccess = 0;
  }

  if (!session_is_registered("changeinfo_lastaccess")) {
    session_register("changeinfo_lastaccess");
    $changeinfo_lastaccess = 0;
  }
?>
