<?php

namespace Drupal\Tests\element_class_formatter\Functional;

use Drupal\entity_test\Entity\EntityTest;
use Drupal\file\Entity\File;
use Drupal\Tests\file\Functional\Formatter\FileMediaFormatterTestBase;

/**
 * Functional tests for the file link with class formatter.
 *
 * @group element_class_formatter
 */
class FileClassFormatterTest extends FileMediaFormatterTestBase {

  const TEST_CLASS = 'test-file-class';

  /**
   * {@inheritdoc}
   */
  public function testClassFormatter() {
    $field_config = $this->createMediaField('file_class', 'pdf', ['class' => self::TEST_CLASS]);

    file_put_contents('public://file.pdf', str_repeat('t', 10));
    $file = File::create([
      'uri' => 'public://file.pdf',
      'filename' => 'file.pdf',
    ]);
    $file->save();

    $entity = EntityTest::create([
      $field_config->getName() => [['target_id' => $file->id()]],
    ]);
    $entity->save();

    $this->drupalGet($entity->toUrl());
    $assert_session = $this->assertSession();
    $assert_session->elementExists('css', 'a.' . self::TEST_CLASS);
  }

}
