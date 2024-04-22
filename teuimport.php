<?php

require_once 'teuimport.civix.php';

use CRM_Teuimport_ExtensionUtil as E;
use \Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Implements hook_civicrm_config().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config/
 */
function teuimport_civicrm_config(&$config): void {
  _teuimport_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
 */
function teuimport_civicrm_install(): void {
  _teuimport_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function teuimport_civicrm_enable(): void {
  _teuimport_civix_civicrm_enable();
}

function teuimport_civicrm_container(ContainerBuilder $container) {
  // Define our local fluent import class
  $container->setParameter('fluent_import_class', \Civi\TEULocalFluentImport::class);
}


// function teuimport_civicrm_container($container) {
//   $container
//     ->findDefinition('dispatcher')
//     ->addMethodCall('addListener', ['civi.fluentimport.register', 'register_our_global_fluentimport_stuff']);
// }

// function register_our_global_fluentimport_stuff($event) {
//   \Civi\FluentImport\Store::registerGlobalMethod('getContactWithDedupe', function (FluentImport $i) use ($petition) {
//     $data = [ 'location' => '', 'activity_date_time' => $i->getCleanValue('activity_date_time') ];
//     $petition->recordConsent($i->getContextValue('contactID'), $data, 62); // xxx
//   });
// }
