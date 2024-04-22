<?php
namespace Civi\FluentImport\Processor;

use Civi\FluentImport;

class TEUPaymentImport extends Job {

  public function runJob(FluentImport $f, array $rawInput): FluentImport {
    $dupeRuleID = 8;
    // $clean = $i->getCleanAccessor();
    // $context = $i->getContextAccessor();
    // 'parent_id' => $context('activity', 'id'),

    $f->setInputValues($rawInput)
      ->clean('ID',[],'profile_id')
      ->clean('Last Name',[],'last_name')
      ->clean('First Name',[],'first_name')
      ->clean('Amount',[], 'amount')
      ->require('profile_id')
      ->getContactUsingDedupeRule(['first_name', 'last_name', 'profile_id'], 'contact', $dupeRuleID)
      ->requireContact()
      ->addActivity([
        'activity_type_id:name' => 'Meeting',
        'subject'               => 'Test Activity: ',
      ])
      ->addContribution([
        'total_amount' => 10,
      ]);

    // Maybe while testing, at the end we do a dump so we can inspect all the details in the log file.
    return $f->dump('Completed');
  }
}
