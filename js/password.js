( function( $ ) {
	uf_password_checker = {
		init : function() {
			
			$( 'input[name=pass1]' ).val( '' ).keyup( uf_password_checker.check_pass_strength );
			$( 'input[name=pass2]' ).val( '' ).keyup( uf_password_checker.check_pass_strength );
			$( '#pass-strength-result' ).show();
		},
		
		check_pass_strength : function () {
			var pass1 = $( 'input[name=pass1]' ).val(),
				user = $( '#user_login' ).val(),
				pass2 = $( 'input[name=pass2]' ).val(), strength;

			$( '#pass-strength-result' ).removeClass( 'short bad good strong' );
			if ( ! pass1 ) {
				$( '#pass-strength-result' ).html( uf_vars.strength_indicator );
				return;
			}

			strength = uf_password_checker.passwordStrength( pass1, user, pass2 );

			switch ( strength ) {
				case 2:
					$( '#pass-strength-result' ).addClass( 'bad' ).html( uf_vars.weak );
					break;
				case 3:
					$( '#pass-strength-result' ).addClass( 'good' ).html( uf_vars.medium );
					break;
				case 4:
					$( '#pass-strength-result' ).addClass( 'strong' ).html( uf_vars.strong );
					break;
				case 5:
					$( '#pass-strength-result' ).addClass( 'short' ).html( uf_vars.mismatch );
					break;
				default:
					$( '#pass-strength-result' ).addClass( 'short' ).html( uf_vars.very_weak );
			}
		},
		
		passwordStrength : function ( f, i, d ) {
		    var k = 1,
		        h = 2,
		        b = 3,
		        a = 4,
		        c = 5,
		        g = 0,
		        j, e;
		    
		    if ( ( f != d ) && d.length > 0 )
		        return c
		        
		    if ( f.length < 4 )
		        return k
		        
		    if ( f.toLowerCase() == i.toLowerCase() )
		        return h
		        
		    if ( f.match(/[0-9]/) )
		        g += 10
		        
		    if ( f.match(/[a-z]/) )
		        g += 26
		        
		    if ( f.match(/[A-Z]/) )
		        g += 26
		        
		    if (f.match(/[^a-zA-Z0-9]/))
		        g += 31
		        
		    j = Math.log( Math.pow( g, f.length ) );
		    e = j / Math.LN2;
		    
		    if ( e < 40 )
		        return h;
		    
		    if ( e < 56 )
		        return b;
		    
		    return a;
		},
	};
	$( document ).ready( function( $ ) {
		uf_password_checker.init();
	} );
} )( jQuery );