(function($) {
    Craft.SyncSettings = Garnish.Base.extend(
        {
            init: function(formId) {
                this.$form = $('#' + formId);
                this.$pruneLightswitch = $("#settings-prune", this.form);
                
                this.addListener(this.$pruneLightswitch, 'change', 'updatePruneSettings');
            },

            updatePruneSettings: function(ev) {
                var $lightswitch = this.$pruneLightswitch.data('lightswitch');
                var $settings = $("#settings-env-sync-prune-settings", this.form);
                if ($lightswitch.on) {                    
                    $settings.show();
                } else {
                    $settings.hide();
                }
            }
        });
})(jQuery);