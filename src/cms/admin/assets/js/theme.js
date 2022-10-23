(function()
{
    /**
     * JS Helper module
     *
     * @var obj
     */
    var Helper = Hubble.helper();

    /**
     * Avatar select wrapper
     *
     * @var node
     */
    var imgWrap = Helper.$('.js-writer-form select[name="type"]');

    console.log(imgWrap);
    
    /**
     *
     * If the wrapper exists add listeners
     */
    if (Helper.nodeExists(imgWrap))
    {
        initAvatarChooser();
    }

    /**
     * Submit event
     *
     */
    function initAvatarChooser()
    {
        var showMediaLibTrigger = Helper.$('.js-select-img-trigger');
        var removeImgTrigger    = Helper.$('.js-remove-img-trigger');
        var setAvatarTrigger    = Helper.$('.js-set-author-avatar');
        var img                 = Helper.$('.js-author-avatar-img img');
        var featureInput        = Helper.$('.js-avatar-id');
        
        Helper.removeClass(Helper.$('.js-update-media'), 'btn-success');
        
        showMediaLibTrigger.addEventListener('click', function(e) {
            e = e || window.event;
            e.preventDefault();
            Helper.addClass(Helper.$('.js-media-library'), 'feature-image');
        });

        setAvatarTrigger.addEventListener('click', function(e) {
            e = e || window.event;
            e.preventDefault();
            Hubble.require('MediaLibrary')._hideLibrary();
            featureInput.value = Helper.$('#media_id').value;
            img.src = Helper.$('#media_url').value;
            Helper.addClass(imgWrap, 'active');
        });

        removeImgTrigger.addEventListener('click', function(e) {
            e = e || window.event;
            e.preventDefault();
            featureInput.value = '';
            Helper.removeClass(imgWrap, 'active');
            img.src = '';
        });
    }   

}()); 
(function() {

    /**
     * @var obj
     */
    var Helper = Hubble.helper();

    /**
     * @var node
     */
    var forms = Helper.$All('.js-validation-form');

    /**
     * If any form validations exist stop them from submitting until valid
     */
    if (!Helper.empty(forms)) {
        for (var i = 0; i < forms.length; i++) {
            initValidations(forms[i]);
        }
    }

    /**
     * Submit event
     *
     */
    function initValidations(form)
    {
        var formValidator = Hubble.require('FormValidator', form);
        var submitBtn     = Helper.$('button[type=submit]', form);
        submitBtn.addEventListener('click', function(e) {
            // Stop the form from submitting
            e = e || window.event;
            e.preventDefault();
            // Validation
            if (formValidator.isValid()) {
                form.submit();
            }
            else {
                formValidator.showInvalid();
            }
        });
    }

}());

(function() {

    /**
     * @var obj
     */
    var Helper = Hubble.helper();

    /**
     * @var node
     */
    var bulkForm = Helper.$('.js-bulk-actions-form');

    /**
     * @var node
     */
    var submitBtn = Helper.$('.js-bulk-actions-form button[type=submit]');

    /**
     * @var node
     */
    var checkAll = Helper.$('.js-list-check-all');

    /**
     * @var node
     */
    var checks = Helper.$All('.js-bulk-action-cb');

     /**
     * @var array
     */
    var deleteTriggers = Helper.$All('.js-confirm-delete');

    /**
     * @var boolean
     */
    var submitting = false;

    // If the sb trigger exists bind the listener
    if (Helper.nodeExists(bulkForm)) {
        submitBtn.addEventListener('click', submitBulkActions);
    }
   
    // If the check all exists and listener
    if (Helper.nodeExists(checkAll)) {
        checkAll.addEventListener('change', toggleCheckAll);
    }

    /**
     * Add Listeners to delete item triggers
     */
    initDeleteTriggers();
    function initDeleteTriggers()
    {
        for (var i = 0; i < deleteTriggers.length; i++) {
            Helper.addEventListener(deleteTriggers[i], 'click', confirmDelete);
        }
    }

    /**
     * Confirm an item delete
     *
     * @param e event
     */
    function confirmDelete(e) {
        e = e || window.event;
        e.preventDefault();
        
        var form = Helper.$('#'+this.dataset.form);
        var item = this.dataset.item;
        
        Hubble.require('Notifications', {
            type           : 'default',
            msg            : 'Are you POSITIVE you want to delete this '+item+'?',
            isCallback     :  true,

            cancelText     : 'No',
            cancelClass    : 'btn-default',
            
            confirmText    : 'Yes delete it!',
            confirmClass   : 'btn-danger',
            onConfirm      : function(args) { form.submit(); },
            onConfirmArgs  : [form],
        });

    }

    
    /**
     * Check/uncheck all the list items
     *
     * @param e event
     */
    function toggleCheckAll(e) {
        if (!Helper.empty(checks)) {
            var doCheck = checkAll.checked;
            for (var i = 0; i < checks.length; i++) {
                checks[i].checked = doCheck;
            }
        }
    }

    /**
     * Submit the bulk action form
     *
     * @param e event
     */
    function submitBulkActions(e) {
       
        // Prevent default
        e = e || window.event;
        e.preventDefault();

        // Prevent double clicks
        if (submitting === true) return;
        submitting = true;

        // Find all the checkboxes
        // and append them to the form then submit
        var checkboxes = Helper.$All('.js-bulk-action-cb');
        if (!Helper.empty(checkboxes)) {
            for (var i = 0; i < checkboxes.length; i++) {
                var clone = checkboxes[i].cloneNode();
                clone.style.display = 'none';
                bulkForm.appendChild(clone);
            }
            bulkForm.submit();
        }
        submitting = false;

        
    }

}());

(function() {

    /**
     * @var obj
     */
    var Helper = Hubble.helper();

    /**
     * @var node
     */
    var sbTrigger = Helper.$('.js-toggle-sb');

    /**
     * @var node
     */
    var sidebar = Helper.$('.js-sidebar');

    /**
     * @var node
     */
    var dashWrap = Helper.$('.js-dash-wrap');

    /**
     * @var array
     */
    var toggleLists = Helper.$All('.js-sidebar .js-toggle-down');

    /**
     * If the sb trigger exists bind the listener
     */
    if (Helper.nodeExists(sbTrigger)) {
        sbTrigger.addEventListener('click', toggleSb);
        window.addEventListener('resize', function() {
            var w = window.innerWidth;
            if (w < 1050) {
                if (Helper.hasClass(sbTrigger, 'active')) {
                    Helper.triggerEvent(sbTrigger, 'click');
                }
            }
        });

        initToggles();
    }
   
    /**
     * Toggle the sidebar
     *
     * @param e event
     */
    function toggleSb(e) {

        // Prevent default
        e = e || window.event;
        e.preventDefault();

        // Toggle classes
        if (Helper.hasClass(sbTrigger, 'active')) {
            Helper.removeClass(sbTrigger, 'active');
            Helper.removeClass(sidebar, 'active');
            Helper.animate(sidebar, 'width', '300px', '66px', 350, 'easeOutQuint');
            Helper.animate(dashWrap, 'padding-left', '324px', '90px', 350, 'easeOutQuint');
            sidebar.scrollTop = 0;
        }
        else {
            Helper.addClass(sbTrigger, 'active');
            Helper.addClass(sidebar, 'active');
            Helper.animate(sidebar, 'width', '66px', '300px', 350, 'easeOutQuint');
            Helper.animate(dashWrap, 'padding-left', '90px', '324px', 350, 'easeOutQuint');
            sidebar.scrollTop = 0;
        }   
    }

    function initToggles()
    {
        for (var i = 0; i < toggleLists.length; i++)
        {
            Helper.addEventListener(toggleLists[i], 'click', toggleList);
        }
    }

    function toggleList(e)
    {
        e = e || window.event;
        var li = this.parentNode;
        Helper.toggleClass(li, 'active');
    }

}());

(function() {

    /**
     * @var obj
     */
    var Helper = Hubble.helper();

    /**
     * @var node
     */
    var trigger = Helper.$('.js-restore-serve-trigger');

    /**
     * Listener on restore submit
     */
    if (Helper.nodeExists(trigger)) {
        Helper.addEventListener(trigger, 'click', confirmRestore);
    }

    /**
     * Submit event
     *
     */
    function confirmRestore(e)
    {
        // Stop the form from submitting
        e = e || window.event;
        e.preventDefault();
        var form = Helper.closest(this, 'form');

        Hubble.require('Modal', {
            type             : 'danger',
            header           : 'danger',
            icon             : 'exclamation-triangle',
            title            : 'Restore Serve',
            message          : 'Are you POSITIVE you want irrevocably delete all data associated with this Serve CMS installation? This cannot be undone.',
            closeText        : 'Cancel',
            closeClass       : 'btn-default',
            confirmClass     : 'btn-danger',
            confirmText      : 'Restore Serve',
            overlay          : 'dark',
            extras           : '',
            validateConfirm  : function() { form.submit(); return true; },

        });
    }

}());
