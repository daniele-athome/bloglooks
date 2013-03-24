<?php


/** Extensions for {@link CHtml}. */
class CHtml2 extends CHtml
{

    /** Generates an image tag for a language flag. */
    public static function flagImage($language)
    {
        return self::image(Yii::app()->baseUrl . '/images/flags/'.$language.'.png', $language, array('title' => $language));
    }

}
