(function ($) {

    /*
     * Create the modal.
     */
    function createModal() {

        if ($('#hellopixel-editor-button-modal').length) {
            return;
        }

        var modal = `
            <div
                id="hellopixel-editor-button-modal"
                class="hellopixel-editor-button-modal"
            >

                <div class="hellopixel-editor-button-modal__overlay"></div>

                <div
                    class="hellopixel-editor-button-modal__window"
                    role="dialog"
                    aria-modal="true"
                    aria-labelledby="hellopixel-editor-button-modal-title"
                >

                    <div class="hellopixel-editor-button-modal__header">

                        <h2 id="hellopixel-editor-button-modal-title">
                            Insert Button
                        </h2>

                        <button
                            type="button"
                            class="hellopixel-editor-button-modal__close"
                            aria-label="Close"
                        >
                            &times;
                        </button>

                    </div>


                    <div class="hellopixel-editor-button-modal__body">

                        <!-- Button text -->

                        <div class="hellopixel-editor-button-field">

                            <label for="hellopixel-editor-button-text">
                                Button text
                            </label>

                            <input
                                type="text"
                                id="hellopixel-editor-button-text"
                                class="regular-text"
                            >

                        </div>


                        <!-- Link type -->

                        <div class="hellopixel-editor-button-field">

                            <label for="hellopixel-editor-button-link-type">
                                Link to
                            </label>

                            <select id="hellopixel-editor-button-link-type">

                                <option value="page">
                                    Page
                                </option>

                                <option value="url">
                                    Custom URL
                                </option>

                            </select>

                        </div>


                        <!-- Page -->

                        <div
                            class="hellopixel-editor-button-field"
                            id="hellopixel-editor-button-page-field"
                        >

                            <label for="hellopixel-editor-button-page">
                                Page
                            </label>

                            <select id="hellopixel-editor-button-page">

                                <option value="">
                                    Loading pages...
                                </option>

                            </select>

                        </div>


                        <!-- Custom URL -->

                        <div
                            class="hellopixel-editor-button-field"
                            id="hellopixel-editor-button-url-field"
                            style="display: none;"
                        >

                            <label for="hellopixel-editor-button-url">
                                URL
                            </label>

                            <input
                                type="url"
                                id="hellopixel-editor-button-url"
                                class="regular-text"
                                placeholder="https://example.com/"
                            >

                        </div>


                        <!-- New tab -->

                        <div class="hellopixel-editor-button-field">

                            <label>

                                <input
                                    type="checkbox"
                                    id="hellopixel-editor-button-new-tab"
                                >

                                Open link in new tab

                            </label>

                        </div>

                        <!-- Button colour -->

                        <div class="hellopixel-editor-button-field">

                            <label for="hellopixel-editor-button-colour">
                                Button colour
                            </label>

                            <select id="hellopixel-editor-button-colour">

                                <option value="ember">
                                    Ember
                                </option>
                                <option value="mulberry">
                                    Mulberry
                                </option>

                            </select>

                        </div>

                    </div>


                    <!-- Footer -->

                    <div class="hellopixel-editor-button-modal__footer">

                        <button
                            type="button"
                            class="button"
                            id="hellopixel-editor-button-cancel"
                        >
                            Cancel
                        </button>

                        <button
                            type="button"
                            class="button button-primary"
                            id="hellopixel-editor-button-insert"
                        >
                            Insert Button
                        </button>

                    </div>

                </div>

            </div>
        `;

        $('body').append(modal);
    }


    /*
     * Load WordPress pages.
     */
    function loadPages() {

        var select = $('#hellopixel-editor-button-page');

        select.html(
            '<option value="">Loading pages...</option>'
        );

        $.ajax({
            url: helloPixelEditorButton.ajaxUrl,
            type: 'POST',

            data: {
                action: 'hellopixel_get_editor_button_pages',
                nonce: helloPixelEditorButton.nonce
            },

            success: function (response) {

                if (!response.success) {

                    select.html(
                        '<option value="">Unable to load pages</option>'
                    );

                    return;
                }

                select.empty();

                select.append(
                    $('<option>', {
                        value: '',
                        text: 'Select a page...'
                    })
                );

                response.data.forEach(function (page) {

                    select.append(
                        $('<option>', {
                            value: page.id,
                            text: page.title,
                            'data-url': page.url
                        })
                    );

                });
            },

            error: function () {

                select.html(
                    '<option value="">Unable to load pages</option>'
                );
            }
        });
    }


    /*
     * Open the modal.
     */
    function openModal(editor) {

        createModal();

        var selectedText = editor.selection.getContent({
            format: 'text'
        });

        $('#hellopixel-editor-button-text').val(
            selectedText || ''
        );

        $('#hellopixel-editor-button-url').val('');

        $('#hellopixel-editor-button-colour').val('ember');

        $('#hellopixel-editor-button-new-tab').prop(
            'checked',
            false
        );

        $('#hellopixel-editor-button-link-type')
            .val('page')
            .trigger('change');

        $('#hellopixel-editor-button-page')
            .val('');

        $('#hellopixel-editor-button-modal')
            .data('editor', editor)
            .addClass('is-open');

        loadPages();

        $('#hellopixel-editor-button-text').trigger('focus');
    }


    /*
     * Close the modal.
     */
    function closeModal() {

        $('#hellopixel-editor-button-modal')
            .removeClass('is-open');
    }


    /*
     * Insert the button into TinyMCE.
     */
    function insertButton() {

        var modal = $('#hellopixel-editor-button-modal');

        var editor = modal.data('editor');

        if (!editor) {
            return;
        }


        var buttonText = $.trim(
            $('#hellopixel-editor-button-text').val()
        );


        var linkType = $(
            '#hellopixel-editor-button-link-type'
        ).val();


        var buttonColour = $(
            '#hellopixel-editor-button-colour'
        ).val();


        var url = '';


        /*
         * Page URL.
         */
        if (linkType === 'page') {

            var selectedPage = $(
                '#hellopixel-editor-button-page option:selected'
            );

            url = selectedPage.data('url') || '';

        }


        /*
         * Custom URL.
         */
        else {

            url = $.trim(
                $('#hellopixel-editor-button-url').val()
            );
        }


        var newTab = $(
            '#hellopixel-editor-button-new-tab'
        ).is(':checked');


        /*
         * Validation.
         */
        if (!buttonText) {

            alert('Please enter button text.');

            $('#hellopixel-editor-button-text')
                .trigger('focus');

            return;
        }


        if (!url) {

            if (linkType === 'page') {

                alert('Please select a page.');

            } else {

                alert('Please enter a URL.');
            }

            return;
        }


        /*
         * New tab attributes.
         */
        var target = newTab
            ? ' target="_blank" rel="noopener noreferrer"'
            : '';


        /*
         * Generate button HTML.
         */
        var html =
            '<a class="btn btn-' +
            editor.dom.encode(buttonColour) +
            '" href="' +
            editor.dom.encode(url) +
            '"' +
            target +
            '>' +
            editor.dom.encode(buttonText) +
            '</a>';


        /*
         * Insert into editor.
         */
        editor.insertContent(html);


        /*
         * Close modal.
         */
        closeModal();
    }


    /*
     * Register the TinyMCE button.
     */
    $(document).on(
        'tinymce-editor-setup',
        function (event, editor) {

            if (
                editor.settings.toolbar1 &&
                editor.settings.toolbar1.indexOf(
                    'hellopixel_editor_button'
                ) === -1
            ) {

                editor.settings.toolbar1 +=
                    ',hellopixel_editor_button';
            }


            editor.addButton(
                'hellopixel_editor_button',
                {
                    text: 'Button',
                    icon: false,

                    onclick: function () {

                        openModal(editor);
                    }
                }
            );
        }
    );


    /*
     * Toggle Page / Custom URL.
     */
    $(document).on(
        'change',
        '#hellopixel-editor-button-link-type',
        function () {

            var type = $(this).val();


            if (type === 'page') {

                $('#hellopixel-editor-button-page-field')
                    .show();

                $('#hellopixel-editor-button-url-field')
                    .hide();

            }


            else {

                $('#hellopixel-editor-button-page-field')
                    .hide();

                $('#hellopixel-editor-button-url-field')
                    .show();
            }
        }
    );


    /*
     * Insert button.
     */
    $(document).on(
        'click',
        '#hellopixel-editor-button-insert',
        function () {

            insertButton();
        }
    );


    /*
     * Cancel / close modal.
     */
    $(document).on(
        'click',
        '#hellopixel-editor-button-cancel, .hellopixel-editor-button-modal__close, .hellopixel-editor-button-modal__overlay',
        function () {

            closeModal();
        }
    );


    /*
     * Escape key closes modal.
     */
    $(document).on(
        'keydown',
        function (event) {

            if (
                event.key === 'Escape' &&
                $('#hellopixel-editor-button-modal').hasClass(
                    'is-open'
                )
            ) {

                closeModal();
            }
        }
    );


})(jQuery);
