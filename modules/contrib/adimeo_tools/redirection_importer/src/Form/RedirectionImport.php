<?php


namespace Drupal\redirection_importer\Form;

use Drupal\adimeo_tools\Shared\BatchTrait;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\file\Entity\File;
use Drupal\redirect\Entity\Redirect;

class RedirectionImport extends FormBase
{

  use BatchTrait;

  const FORM_ID = 'redirection_importer.redirection_import';
  const FIELD_FILE = 'file';
  const SEPARATOR = ';';
  const WRAPPER = '"';
  /**
   * Returns a unique string identifying the form.
   *
   * The returned ID should be a unique string that can be a valid PHP function
   * name, since it's used in hook implementation names such as
   * hook_form_FORM_ID_alter().
   *
   * @return string
   *   The unique string identifying the form.
   */
  public function getFormId()
  {
    return static::FORM_ID;
  }

  /**
   * Form constructor.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   *
   * @return array
   *   The form structure.
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {
    $form[static::FIELD_FILE] = [
      '#type'              => 'managed_file',
      '#title'             => t('Fichier'),
      '#upload_validators' => [
        'file_validate_extensions' => ['csv'],
        'file_validate_size'       => array(25600000)
      ],
      '#description' => 'Type: csv
                               <br/>séparateur: ";"
                               <br/>wrapper: """<br/>

                               <br/>Pas d\'entête de colonne,
                               <br/>ordre: "source"(url à redirigée ex: /node/4501);"redirection"(url vers laquelle on redirige ex: internal:/node/4502 ou);"language"(code lang ex: fr, en);"status code"(301 to permanante redirection)',
    ];

    $form['submit'] = [
      '#type'        => 'submit',
      '#value'       => t('Save'),
      '#button_type' => 'primary',
    ];

    return $form;
  }

  /**
   * Form submission handler.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $dataImport = $this->getDataToImport($form_state);

    $operations = $this->getBatchOperations(
      '\\' . get_called_class() . '::processLine',
      $dataImport
    );

    // Launch batch.
    $batch = array(
      'title'      => 'Import des metatags',
      'operations' => $operations,
      'finished'   => '\\' . get_called_class() . '::processEnd',
    );
    batch_set($batch);

  }

  /**
   * Retourne la liste des toutes les données à importer.
   *
   * @return array
   *   La liste de données.
   */
  protected function getDataToImport(FormStateInterface $formState) {
    $nodesDataList = [];

    // On récupère le fichier.
    if ($file = File::load($formState->getValue(static::FIELD_FILE)[0])) {
      // On parse le fichier.
      $path = $file->getFileUri();

      if ($path) {
        if ($handle = fopen($path, 'r')) {
          while (FALSE !== ($data = fgetcsv($handle, NULL, static::SEPARATOR, static::WRAPPER))) {
            $nodesDataList[] = $data;
          }
        }
      }

      return $nodesDataList;
    }

    return [];
  }

  public static function processLine(array $data, array &$context) {

    if (!array_key_exists('errors', $context['results'])) {
      $context['results']['errors'] = [];
    }
    // On stocke la ligne courante, c'est plus simple pour l'accès.
    /** @var static $form */
    $form = new static();
    foreach ($data as $line) {
      if(is_null($data)) {
        continue;
      }
      $context['results']['imported'] += $form->importRedirection($line, $context['results']['errors']) ? 1 : 0;
      $context['results']['total']++;
    }
  }

  public function importRedirection($data, &$errors) {

    Redirect::create([
      'redirect_source' => $data[0], // Set your custom URL.
      'redirect_redirect' => $data[1], // Set internal path to a node for example.
      'language' => $data[2], // Set the current language or undefined.
      'status_code' => $data[3], // Set HTTP code.
    ])->save();

    return TRUE;
  }

  /**
   * Fin du process batch.
   *
   * @param bool $success
   *   Données de succès.
   * @param array $results
   *   Données de résultat.
   * @param array $operations
   *   Les opérations.
   */
  public static function processEnd($success, array $results, array $operations) {

    if ($success) {
      $message = count($results) . ' processed.';
    }
    \Drupal::messenger()->addMessage(t($message));
  }
}
