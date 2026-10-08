(function ($) {

    'use strict';

    /*
     * The focal-point field structure is:
     *
     * img_w_focal
     * ├── img
     * ├── img_focal_x
     * └── img_focal_y
     */

    const groupFieldName = 'img_w_focal';
    const imageFieldName = 'img';
    const focusXFieldName = 'img_focal_x';
    const focusYFieldName = 'img_focal_y';


    /**
     * Initialise all focal point groups.
     */
    function initFocalPointPicker() {

        const $groups = $('.acf-field[data-name="' + groupFieldName + '"]');

        if (!$groups.length) {
            return;
        }

        $groups.each(function () {

            const $group = $(this);

            // Don't initialise the same group twice.
            if ($group.data('focal-point-initialised')) {
                return;
            }

            const $imageField = getChildField(
                $group,
                imageFieldName
            );

            const $xField = getChildField(
                $group,
                focusXFieldName
            );

            const $yField = getChildField(
                $group,
                focusYFieldName
            );

            /*
             * Only initialise if the expected structure exists.
             */
            if (
                !$imageField.length ||
                !$xField.length ||
                !$yField.length
            ) {
                return;
            }

            $group.data('focal-point-initialised', true);

            createPicker(
                $group,
                $imageField,
                $xField,
                $yField
            );

        });

    }


    /**
     * Get a child field from the focal point group.
     */
    function getChildField(
        $group,
        fieldName
    ) {

        return $group
            .find('.acf-field[data-name="' + fieldName + '"]')
            .first();

    }


    /**
     * Create the focal point picker.
     */
    function createPicker(
        $group,
        $imageField,
        $xField,
        $yField
    ) {

        const $picker = $(`
            <div class="hp-focal-picker">

                <div class="hp-focal-picker__header">
                    <strong>Focus Point</strong>
                    <span>Click the image to set the focal point.</span>
                </div>

                <div class="hp-focal-picker__image-wrap">
                    <img
                        class="hp-focal-picker__image"
                        src=""
                        alt=""
                    >
                    <span class="hp-focal-picker__marker"></span>
                </div>

            </div>
        `);

        /*
         * Put the picker after the image field.
         */
        $imageField.after($picker);

        const $image = $picker.find(
            '.hp-focal-picker__image'
        );

        const $marker = $picker.find(
            '.hp-focal-picker__marker'
        );


        /*
         * Update the picker initially.
         */
        updatePickerImage(
            $imageField,
            $image,
            $marker,
            $xField,
            $yField
        );


        /*
         * Watch the image field for changes.
         *
         * This catches:
         * - selecting an image
         * - replacing an image
         * - removing an image
         */
        const observer = new MutationObserver(function () {

            updatePickerImage(
                $imageField,
                $image,
                $marker,
                $xField,
                $yField
            );

        });


        observer.observe(
            $imageField[0],
            {
                childList: true,
                subtree: true,
                attributes: true,
                attributeFilter: ['src']
            }
        );


        /*
         * Clicking the image sets the focal point.
         */
        $picker.on(
            'click',
            '.hp-focal-picker__image',
            function (event) {

                const image = this;
                const rect = image.getBoundingClientRect();

                let x = (
                    (event.clientX - rect.left) /
                    rect.width
                ) * 100;

                let y = (
                    (event.clientY - rect.top) /
                    rect.height
                ) * 100;


                /*
                 * Keep values between 0 and 100.
                 */
                x = Math.max(
                    0,
                    Math.min(100, x)
                );

                y = Math.max(
                    0,
                    Math.min(100, y)
                );


                /*
                 * Save values to the SCF/ACF fields.
                 */
                setFocalPoint(
                    $xField,
                    $yField,
                    x,
                    y
                );


                /*
                 * Immediately move the marker.
                 */
                updateMarker(
                    $marker,
                    x,
                    y
                );

            }
        );


        /*
         * Initialise marker from existing values.
         */
        updateMarkerFromFields(
            $xField,
            $yField,
            $marker
        );

    }


    /**
     * Get the image currently selected in the image field.
     */
    function getImageUrl($imageField) {

        const $preview = $imageField
            .find('.acf-image-uploader img')
            .first();

        if (!$preview.length) {
            return '';
        }

        return $preview.attr('src') || '';

    }


    /**
     * Update the picker image.
     */
    function updatePickerImage(
        $imageField,
        $image,
        $marker,
        $xField,
        $yField
    ) {

        const imageUrl = getImageUrl(
            $imageField
        );


        /*
         * No image selected.
         */
        if (!imageUrl) {

            $image.attr(
                'src',
                ''
            );

            $image
                .closest('.hp-focal-picker')
                .hide();

            return;

        }


        /*
         * Only update the image if it has changed.
         */
        if ($image.attr('src') !== imageUrl) {

            $image.attr(
                'src',
                imageUrl
            );

        }


        /*
         * Show picker.
         */
        $image
            .closest('.hp-focal-picker')
            .show();


        /*
         * Restore marker position.
         */
        updateMarkerFromFields(
            $xField,
            $yField,
            $marker
        );

    }


    /**
     * Get an existing focal point.
     */
    function getFocalPoint(
        $xField,
        $yField
    ) {

        let x = parseFloat(
            $xField.find('input').first().val()
        );

        let y = parseFloat(
            $yField.find('input').first().val()
        );


        /*
         * Default to the centre of the image.
         */
        if (isNaN(x)) {
            x = 50;
        }

        if (isNaN(y)) {
            y = 50;
        }


        return {
            x: x,
            y: y
        };

    }


    /**
     * Save the focal point into the SCF/ACF fields.
     */
    function setFocalPoint(
        $xField,
        $yField,
        x,
        y
    ) {

        /*
         * Round to two decimal places.
         */
        x = Math.round(x * 100) / 100;
        y = Math.round(y * 100) / 100;


        const $xInput = $xField.find(
            'input'
        ).first();

        const $yInput = $yField.find(
            'input'
        ).first();


        $xInput
            .val(x)
            .trigger('change');

        $yInput
            .val(y)
            .trigger('change');

    }


    /**
     * Update the marker position.
     */
    function updateMarker(
        $marker,
        x,
        y
    ) {

        $marker.css({
            left: x + '%',
            top: y + '%'
        });

    }


    /**
     * Set marker position from SCF/ACF fields.
     */
    function updateMarkerFromFields(
        $xField,
        $yField,
        $marker
    ) {

        const focalPoint = getFocalPoint(
            $xField,
            $yField
        );


        updateMarker(
            $marker,
            focalPoint.x,
            focalPoint.y
        );

    }


    /*
     * Initialise on page load.
     */
    $(document).ready(function () {

        initFocalPointPicker();

    });


    /*
     * SCF/ACF can dynamically add fields.
     *
     * This makes the picker work inside:
     * - repeaters
     * - flexible content
     * - dynamically appended fields
     */
    if (typeof acf !== 'undefined') {

        acf.addAction(
            'ready',
            function () {

                initFocalPointPicker();

            }
        );


        acf.addAction(
            'append',
            function () {

                initFocalPointPicker();

            }
        );

    }


})(jQuery);
