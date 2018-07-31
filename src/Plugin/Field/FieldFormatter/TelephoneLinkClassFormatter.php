<?php

namespace Drupal\element_class_formatter\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\telephone\Plugin\Field\FieldFormatter\TelephoneLinkFormatter;

/**
 * Plugin implementation of the 'telephone_link_class' formatter.
 *
 * @FieldFormatter(
 *   id = "telephone_link_class",
 *   label = @Translation("Telephone link (with class)"),
 *   field_types = {
 *     "telephone"
 *   }
 * )
 */
class TelephoneLinkClassFormatter extends TelephoneLinkFormatter {

  use ElementClassTrait;

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return ElementClassTrait::linkClassDefaultSettings(parent::defaultSettings());
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $elements = parent::settingsForm($form, $form_state);
    $class = $this->getSetting('class');

    return $this->linkClassSettingsForm($elements, $class);
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = parent::settingsSummary();
    $class = $this->getSetting('class');

    return $this->linkClassSettingsSummary($summary, $class);
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = parent::viewElements($items, $langcode);
    $class = $this->getSetting('class');

    return $this->setLinkClass($elements, $class, $items);
  }

}
