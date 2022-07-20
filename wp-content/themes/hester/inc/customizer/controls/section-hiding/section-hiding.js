/**
 * Scripts file for the Show/Hide frontpage control in customizer
 *
 * @package hester
 */

/* global jQuery */
/* global wp */
( function( $ ) {
  $( window ).on( 'load',
    async function() {
      'use strict';

      function waitForElm( selector ) {
        return new Promise( resolve => {
          if ( $( selector ).length ) {
            return resolve( $( selector ) );
          }

          const observer = new MutationObserver( mutations => {
            if ( $( selector ).length ) {
              resolve( $( selector ) );
              observer.disconnect();
            }
          });

          observer.observe( document.body, {
            childList: true,
            subtree: true
          });
        });
      }

      const toggleSection = await waitForElm( '.hester-toggle-section' );

      /**
       * Fix for icons when they are in changeset is active
       */
      toggleSection.each(
        function() {
          var controlName = $( this ).data( 'control' );
          var controlValue;
          if ( 'undefined' !== typeof wp.customize.control( controlName ) ) {
            controlValue = wp.customize.control( controlName ).setting.get();
          }

          var parentHeader = $( this ).parent();
          if ( 'undefined' !== typeof ( controlName ) && '' !== controlName ) {
            var iconClass = 'dashicons-visibility';
            if ( false === controlValue ) {
              iconClass = 'dashicons-hidden';
              parentHeader.addClass( 'hester-section-hidden' ).removeClass( 'hester-section-visible' );
            } else {
              parentHeader.addClass( 'hester-section-visible' ).removeClass( 'hester-section-hidden' );
            }
            $( this ).children().attr( 'class', 'dashicons ' + iconClass );
          }
        }
      );

      toggleSection.on(
        'click', function( e ) {
          e.stopPropagation();
          var controlName = $( this ).data( 'control' );
          var parentHeader = $( this ).parent();
          var controlValue = wp.customize.control( controlName ).setting.get();

          if ( 'undefined' !== typeof ( controlName ) && '' !== controlName ) {
            var iconClass = 'dashicons-visibility';

            /* Compare with true because value already changed when triggered this function */
            if ( true === controlValue ) {
              iconClass = 'dashicons-hidden';
              parentHeader.addClass( 'hester-section-hidden' ).removeClass( 'hester-section-visible' );
            } else {
              parentHeader.addClass( 'hester-section-visible' ).removeClass( 'hester-section-hidden' );
            }
            wp.customize.control( controlName ).setting.set( ! controlValue );
            document.getElementById( controlName ).click();
            $( this ).children().attr( 'class', 'dashicons ' + iconClass );
          }
        }
      );

      $( 'ul' ).find( '.hester-toggle' ).on(
        'click', function() {
          var showHideControls = hester_customizer_sections.sections.map( function( section ) {
            return 'hester_enable_' + section;
          });

          var controlName = $( this ).children( 'input' ).attr( 'name' );
          if ( -1 >= showHideControls.indexOf( controlName ) ) {
            return;
          }
          var sectionName = $( this ).parent().parent().parent().attr( 'id' );
          sectionName = sectionName.replace( 'sub-', '' );
          var parentHeader = $( '#' + sectionName ).find( '.accordion-section-title' );
          if ( 'undefined' !== typeof ( sectionName ) && '' !== sectionName ) {

            if ( wp.customize.control( controlName ) && wp.customize.control( controlName ).setting ) {
              var controlValue = wp.customize.control( controlName ).setting.get();

              var iconClass = 'dashicons-visibility';
              if ( false === controlValue ) {
                iconClass = 'dashicons-hidden';
                parentHeader.addClass( 'hester-section-hidden' ).removeClass( 'hester-section-visible' );
              } else {
                parentHeader.addClass( 'hester-section-visible' );
                parentHeader.removeClass( 'hester-section-hidden' );
              }
              parentHeader.find( '.hester-toggle-section' ).children().attr( 'class', 'dashicons ' + iconClass );
            }
          }
        }
      );
    }
  );
}( jQuery ) );
