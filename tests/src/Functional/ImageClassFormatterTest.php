<?php

namespace Drupal\Tests\element_class_formatter\Functional;

use Drupal\entity_test\Entity\EntityTest;
use Drupal\file\Entity\File;
use Drupal\Tests\file\Functional\Formatter\FileMediaFormatterTestBase;

/**
 * Functional tests for the image link with class formatter.
 *
 * @group element_class_formatter
 */
class ImageClassFormatterTest extends FileMediaFormatterTestBase {

  const TEST_CLASS = 'test-image-class';

  /**
   * {@inheritdoc}
   */
  public function testClassFormatter() {
    $field_config = $this->createMediaField('image_class', 'png', ['class' => self::TEST_CLASS]);

    $image = $this->getTestFiles('image')[0];
    $file = File::create([
      'uri' => $image->uri,
      'status' => 1,
    ]);
    $file->save();

    $entity = EntityTest::create([
      $field_config->getName() => [['target_id' => $file->id()]],
    ]);
    $entity->save();

    $this->drupalGet($entity->toUrl());
    $assert_session = $this->assertSession();
    $assert_session->elementExists('css', 'img.' . self::TEST_CLASS);
  }

}
