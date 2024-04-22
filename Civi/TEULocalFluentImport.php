<?php
namespace Civi;

class TEULocalFluentImport extends FluentImport {


  public function getContactUsingDedupeRule($cleanKeys, $contextKey = 'contact', $ruleGroupID = NULL) {
    if (!$this->alive) {
      return $this;
    }

    // Skip if the contact is already identified (e.g. by checksum/identifyContact).
    if ($this->getContextValue($contextKey)) {
      // Civi::log()->debug("skipping getOrCreate as $contextKey already exists");
      return $this;
    }

    // Use unsupervised de-dupe rule.
    $input = [];
    foreach ($cleanKeys as $name) {
      $input[$name] = $this->getCleanValue($name);
    }
    $contactID = \CRM_Contact_BAO_Contact::getFirstDuplicateContact($input, 'Individual', 'General', [], FALSE, $ruleGroupID);
    if ($contactID) {
      $this->track('Contact', $contactID, 'found');
      $this->store->setContext($contextKey, ['id' => $contactID, 'wasCreated' => FALSE]);
    }
    return $this;
  }


  /**
   * This helper creates an contribution with typical defaults.
   *
   * @param array|Civi\Api4\Generic\DAOCreateAction $params
   * - a Civi\Api4\Generic\DAOCreateAction
   * - an array, it is assumed to be the values and will be passed in with setValues()
   *
   * In each case, we add defaults and execute the API call. The result is stored in context.
   *
   * Defaults: main contact is the target; source contact is the logged in user; status is Completed.
   *
   * @return static
   */
  public function addContribution($params, $contextKey = 'contribution') {
    if (!$this->alive) {
      return $this;
    }

    if ($params instanceof \Civi\Api4\Generic\DAOCreateAction) {
      $api = $params;
      $setValues = $api->getValues();
    }
    elseif (is_array($params)) {
      $setValues = $params;
      $api = \Civi\Api4\Contribution::create(FALSE)->setValues($params);
    }

    if (!isset($setValues['contact_id'])) {
      $this->requireContact();
      $api->addValue('contact_id', $this->getContactID());
    }
    if (!isset($setValues['financial_type_id:label'])) {
      $api->addValue('financial_type_id:label', 'Member Dues');
    }
    if (!isset($setValues['contribution_status_id']) && !isset($setValues['contribution_status_id:name'])) {
      $api->addValue('contribution_status_id:name', 'Completed');
    }
    $api->addValue('source', 'Bulk Import ' . date('d-m-Y'));

    $contribution = $api->execute()->first();
    $this->store->setContext($contextKey, $contribution);
    if ($contribution) {
      $this->track('Contribution', $contribution['id'], 'found');
    }

    return $this;
  }

}
