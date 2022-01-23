<?php

namespace Drupal\adimeo_apm_tracking\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class ConfigForm.
 */
class ConfigForm extends ConfigFormBase
{
  const CONFIG_KEY = 'apm_tracking.config';

  const API_KEY_PARAM = 'apiKey';

  /**
   * API method
   *
   * @var string
   * @todo remove occurences, now useless
   */
  const API = 'api';

  /**
   * {@inheritdoc}
   */
  public function getFormId()
  {
    return 'apm_tracking_admin_config';
  }

  /**
   * @return array|void
   */
  protected function getEditableConfigNames()
  {
    return [
      static::CONFIG_KEY,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {
    $config = $this->config(self::CONFIG_KEY);

    $form['apiKey'] = [
      '#type' => 'textfield',
      '#title' => $this->t('API key'),
      '#description' => $this->t('Allowed API Key to get the status reports'),
      '#placeholder' => '',
      '#default_value' => $config->get(self::API_KEY_PARAM),
      '#maxlength' => 64,
      '#size' => 64,
      '#weight' => '0',
      '#required' => TRUE
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Enregistrer'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $config = $this->configFactory->getEditable(self::CONFIG_KEY);
    $config->set(self::API_KEY_PARAM, $form_state->getValue(self::API_KEY_PARAM));
    $config->save();

    $this->messenger()->addMessage("Config was saved successfully");
  }

}
