<?php

use Concrete\Package\BaclucC5Crud\Src\Import\ComparisonSet;

/*
 * Created by PhpStorm.
 * User: lucius
 * Date: 22.03.17
 * Time: 09:16.
 */
?>
<h1><?php echo t('Import View for %s', $controller->getHeader()); ?></h1>
<form action="<?php echo $this->action('persistImport'); ?>" method="post">
    <input type="hidden" name="ccm_token" value="<?php echo $token; ?>"/>

    <?php
    if (strlen($errorMessage) > 0) {
        echo '<div class="errormessage alert-danger">
                '.$errorMessage.'
                </div>
            ';
    }
?>
    <?php
$fields = $controller->getFields();
if (count($comparisondata) > 0) {
    $classname = get_class($comparisondata[0]->getImportModel());
    $uniqueFunction = $classname::getDefaultGetDisplayStringFunction();

    foreach ($comparisondata as $num => $comparisonset) {
        ?>
            <div class="importrow">
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                    <tr>
                        <?php

                    foreach ($fields as $fieldname => $type) {
                        if ('id' == $fieldname) {
                            ?>
                                <th width='20%' data-column-id="commands" data-formatter="commands"
                                    data-sortable="false"><?php echo t('Type'); ?></th>


                            <?php
                        } else {
                            ?>

                                <th data-column-id='<?php echo $type->getPostName(); ?>'
                                    data-formatter="<?php echo (new ReflectionClass($type))->getShortName(); ?>"><?php echo t($type->getLabel()); ?></th>
                                <?php
                        }
                    } ?>
                        <!-- add column for unique string-->
                        <th data-column-id='uniquestring'
                            data-formatter="text"><?php echo t('Unique String'); ?></th>
                    </tr>
                    </thead>
                    <tbody>


                    <?php

                    /**
                     * @var ComparisonSet $comparisonset
                     */
                    $isnewEntry = false;
        foreach ($comparisonset as $modeltype => $model) {
            echo '<tr>';
            if (ComparisonSet::KEY_CURRENT == $modeltype
                        && is_null($model->get($model->getIdFieldName()))) {
                $model = $comparisonset->getResultModel();
                echo '<th>'.ComparisonSet::KEY_NEWENTRY
                             .'</th>';
                $isnewEntry = true;
            } else {
                echo '<th>'.$modeltype.'</th>';
            }

            foreach ($fields as $colname => $field) {
                if ($colname == $controller->getModel()->getIdFieldName()) {
                } else {
                    $field->setSQLValue($model->get($colname));

                    // var_dump($field);
                    echo '<td>'.$field->getTableView().'</td>';
                }
            }

            echo '<td>'.$uniqueFunction($model).'</td>';
            if ($isnewEntry) {
                // if it is a new entry, we need only one row
                break;
            }
        }
        echo '</tbody></table>
    
            '; ?>
                    <div class="acceptchange">
                        <label><?php echo t('Accept change'); ?></label><input type="checkbox"
                                                                               name="acceptImport[<?php echo $num; ?>]"/>
                    </div>
            </div>
            <?php
    }
}
?>
    <input class='btn btn-primary' type="submit" value="<?php echo t('Submit selected changes'); ?>">
</form>