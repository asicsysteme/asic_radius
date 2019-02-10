<?php 
//Export Module 'Systeme' Date: 29-01-2016
global $db;
$result_insert_modul = $db->Query("insert into modul (modul, description, app_modul, etat)values('Systeme', 'Applications utilises par le Systeme','tdb', '0')");
  //Task 'login'
  $result_task_1 = $db->Query("insert into task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat)values('login', $result_insert_modul, 'login','login', '0', 'Connexion', 0, '0', '1', '0')");
  //Task 'forgot'
  $result_task_2 = $db->Query("insert into task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat)values('forgot', $result_insert_modul, 'forgot','login', '0', 'Mot de passe oublie', 0, '0', '1', '0')");
  //Task 'tdb'
  $result_task_3 = $db->Query("insert into task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat)values('tdb', $result_insert_modul, 'tdb','tdb', '1', 'Tableau de bord', 0, '0', '1', '0')");
  //Task 'logout'
  $result_task_4 = $db->Query("insert into task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat)values('logout', $result_insert_modul, 'logout','login', '1', 'Deconnexion', 0, '0', '1', '0')");
  //Task 'recovery'
  $result_task_5 = $db->Query("insert into task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat)values('recovery', $result_insert_modul, 'recovery','login', '0', 'reinitialisation', 0, '0', '1', '0')");
  //Task 'loadenselect'
  $result_task_10 = $db->Query("insert into task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat)values('loadenselect', $result_insert_modul, 'loadenselect','ajax', '0', 'remplir select', 0, '1', '1', '0')");
  //Task 'check'
  $result_task_12 = $db->Query("insert into task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat)values('check', $result_insert_modul, 'check','ajax', '1', 'Check All', 0, '1', '1', '0')");
  //Task 'shopdf'
  $result_task_15 = $db->Query("insert into task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat)values('shopdf', $result_insert_modul, 'shopdf','ajax', '1', 'PDF Viewer', 0, '1', '1', '0')");
  //Task 'tooltip'
  $result_task_22 = $db->Query("insert into task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat)values('tooltip', $result_insert_modul, 'tooltip','ajax', '1', 'annotation', 0, '1', '1', '0')");
  //Task 'dbd'
  $result_task_23 = $db->Query("insert into task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat)values('dbd', $result_insert_modul, 'dbd','tdb', '1', 'Tableau de bord', 0, '0', '1', '0')");
  //Task 'upload'
  $result_task_27 = $db->Query("insert into task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat)values('upload', $result_insert_modul, 'upload','ajax', '1', 'Uploder', 0, '1', '1', '0')");
  //Task 'errorjs'
  $result_task_31 = $db->Query("insert into task (app, modul, file, rep, session, dscrip, sbclass, ajax, app_sys, etat)values('errorjs', $result_insert_modul, 'errorjs','ajax', '0', 'Erreur JS', 0, '0', '1', '0')");
