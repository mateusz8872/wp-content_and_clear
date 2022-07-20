// Toggle control
wp.customize.controlConstructor[ 'hester-toggle' ] = wp.customize.Control.extend({
	ready: function() {
		"use strict";

		var control = this;

		// Change the value
		control.container.on( 'click', '.hester-toggle-switch', function() {
			control.setting.set( ! control.setting.get() );
		});
	}
});