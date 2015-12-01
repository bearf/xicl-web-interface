(function($, xicl) {

	var tasks_;

	var REVEAL_MODE = false;

	xicl.monitor = function(contest) {
		var queue = [],
			since = 0,
			v = false,
			duration,
			left,
			gone
			atTop = true;

		$.getJSON( 'user/json/' ).done( function(users) {
			$.each( users, function(key, tm) {
				team( tm );
			} );
			check();
		} );

		function check() {
			setTimeout( function() {
				queue.length ? update() : load()
			}, 2000 );
		}

		function update() {
			updateSubmit( queue.shift(), true ).done( check );
		}

		function load() {
			$.getJSON( 'status/json/?contest=' + contest + '&since=' + since ).done( function(response) {
				tasks_ = response.tasks;
				duration = response.duration;
				left = response.left;
				gone = response.gone;
				// fixme
				if (response.submits.length) {
					since = response.submits[response.submits.length-1].submitId;
				} 

				if (!v) {
					v = true;
					response.submits.forEach( function(submit) {
						applySubmit( submit ); 
					} );
					response.submits = [];
					visualize();
					ut();
				} 

				if (response.submits.length) {
					queue.push.apply( queue, response.submits );
					check();
				} else {
					ut();
					setTimeout( check, 30000 );
					if (Math.random() < 0.67) {
						atTop ? down() : up();
						atTop = !atTop;
					}
				}
			} );
		}

		function up() {
			turtleScroll().done( function() {
				$el().css( 'marginTop', 0 );
			} );
		}

		function down() {
			turtleScroll().done( function() {
				$el().css( 'marginTop', -($el().height() - $( window ).height() + 128) + 'px' );
			} );
		}

		function ut(cur) {
			if (cur) {
				$( document.body ).removeClass( 'finished not-started frozen' );
				updateTime( cur );
			} else if (gone < 0) {
				$( document.body ).removeClass( 'finished frozen' )
					.addClass( 'not-started' );
				updateTime( 0 );
			} else if (left > 0) {
				updateTime( gone );
				if (!adminMode() && left < 3600) {
					$( document.body ).removeClass( 'not-started finished' )
						.addClass( 'frozen' );
				} else {
					$( document.body ).removeClass( 'finished not-started frozen' );
				}
			} else {
				updateTime( duration );
				adminMode() 
					? $( document.body ).removeClass( 'not-started frozen' )
						.addClass( 'finished' )
					: $( document.body ).removeClass( 'not-started finished' )
						.addClass( 'frozen' );					
			}
		}
	}

	var diploma;

	xicl.reveal = function(contest, diploma_) {
		REVEAL_MODE = true;
		diploma = diploma_;

		$.ajax( {
			url:'status/json?contest=' + contest
		} ).fail( function(e, r, s) {
			console.log( e, r, s );
		} ).done( function(response) {
			tasks_ = response.tasks;

			response.submits.sort( function(a, b) {
				return a.submitId - b.submitId;
			} ).forEach( function(submit) {
				// remember team from submit into teams' cache
				team( submit );

				frozen( submit )
					? postpone( submit )
					: applySubmit( submit );
			} );

			visualize();

			revealRow( teams().length - 1 );
		} );
	}

	function revealMode() {
		return REVEAL_MODE;
	}

	var current;

	function anyKey() {
		return $.Deferred( function(def) {
			$( document.body ).keypress( handler ).addClass( 'any-key' );

			def.done( function() {
				$( document.body ).removeClass( 'any-key' ).unbind( 'keypress', handler );
			} );

			function handler() {
				def.resolve();
			}
		} ).promise();
	}

	function revealRow(rowIndex) {
		visualize();

		var tm = teams()[rowIndex];
		slowScroll().done( function() {
			scrollTo( tm ).done( function() {
				$rows().removeClass( 'current' )
					.eq( rowIndex ).addClass( 'current' );

				anyKey().done( function() {

					revealSubmits( tm ).done( function() {
						revealTeam( tm ).done( function() {
							rowIndex > 0 && revealRow( rowIndex - 1 );
						} );
					} ).fail( function() {
						revealRow( rowIndex );
					} );

				} );
			} );
		} );
	}

	function teamIndex(tm) {
		return place = teams().filter( function(anotherTeam) {
			return anotherTeam.division === tm.division;
		} ).indexOf( tm ) + 1;
	}

	function diplomaArray(tm) {
		return diploma[1 == tm.division ? 'student' : 'school'];
	}

	function teamDiploma(tm) {
		var diploma = diplomaArray( tm ),
			index = teamIndex( tm );
		if (index <= diploma[0]) {
			return 'Диплом I степени';
		} else if (index <= diploma[0] + diploma[1]) {
			return 'Диплом II степени'
		} else if (index <= diploma[0] + diploma[1] + diploma[2]) {
			return 'Диплом III степени';
		} else if (index <= diploma[0] + diploma[1] + diploma[2] + diploma[3]) {
			return 'Почетная грамота';
		} else {
			return 'Сертификат участника';
		}
	}

	function teamIndexable(tm) {
		var diploma = diplomaArray( tm );
		return teamIndex( tm ) <= diploma[0] + diploma[1] + diploma[2];
	}

	function revealTeam(tm) {
		return $.Deferred( function(def) {
			anyKey().done( function() {
				// leave teams from current division only
				var ctx = $.extend( { index:teamIndex( tm ) }, tm );
				ctx.division = 1 == tm.division
					? 'студенческом'
					: 'школьном';
				ctx.indexable = teamIndexable( tm );
				ctx.diploma = teamDiploma( tm );
				$( 'section' ).html(
					Handlebars.compile( '\
						<p><strong>{{diploma}}</strong></p>\
						{{#indexable}}<p>{{index}} место в {{division}} зачете</p>{{/indexable}}\
						<p><strong>{{nickname}}</strong> ({{city}})</p>\
						<p>{{studyplace}}</p>\
						{{& info}}\
					' ) ( ctx )
				);
				$( 'header' ).addClass( 'info' );
				setTimeout( function() {
					anyKey().done( function() {
						$( 'header' ).removeClass( 'info' );
						setTimeout( function() {
							def.resolve();
						}, 500 );
					} );
				}, 500 );
			} );
		} ).promise();
	}

	function revealSubmits(tm) {
		return $.Deferred( function(def) {
			var submit = tm.shiftSubmit();

			if (!submit) {
				return def.resolve();
			}

			updateSubmit( submit ).done( function() {
				if (ok( submit )) {
					anyKey().done( function() {
						def.reject();
					} );
				} else {
					revealSubmits( tm ).done( function() {
						def.resolve();
					} ).fail( function() {
						def.reject();
					} );
				}
			} );
		} ).promise();
	}

	function updateSubmit(submit, scroll) {
		return $.Deferred( function(def) {
			var tm = team( submit ),
				rowIndex = teams().indexOf( tm ),
				$row = $rows().eq( rowIndex );

			$rows().removeClass( 'current' );
			$row.addClass( 'current' );

			if (scroll) {
				slowScroll().done( function() {
					scrollTo( team( submit ) ).done( scrolled );
				} );
			} else {
				setTimeout( function() {
					scrolled();
				}, 0 );
			}

			function scrolled() {
				updateTime( submit.time );

				var result = applySubmit( submit );
				animateSubmit( submit ).done( function() {
					if (result) {
						slowAnimateRow( team( submit ) ).done( function() {
							visualize();
							def.resolve();
						} );
					} else {
						visualize();
						def.resolve();
					}
				} );
			}

			function immediate() {
				updateTime( submit.time );
				animateSubmit( submit )

				if (applySubmit( submit )) {
					animateRow( team( submit ) );
					sortTeams( teams() );
				}  

				visualize();

				def.resolve();
			}
		} ).promise();
	}

	function animateSubmit(submit) {
		var code = submit.task.charCodeAt( 0 ) - 'A'.charCodeAt( 0 ),
			index,
			$row = $rows().filter( function(i) {
				var s = $( this ).children().eq( 1 ).text().trim();
				return 0 === submit.nickname.indexOf( s );
			} );

		var ctx = serialize( team( submit ) );
		var $nr = $( rowTemplate() ( ctx ) ).addClass( 'current' );
		// todo: current
		$row.replaceWith( $nr );
		$row = $nr;

		var offset = $row.children().eq( code + 2 ).offset(),
			def = $.Deferred();

		$cell = $( '<div class="verdict"></div>' )
			.css( {
				top:offset.top,
				left:offset.left,
			} )
			.html( submit.result )
			.appendTo( document.body );

		var $marker = $( '<div class="marker">&nbsp;</div>' )
			.addClass( ok( submit ) ? 'accepted' : 'rejected' )
			.css( {
				top:offset.top,
				left:offset.left
			} )
			.appendTo( document.body );

		setTimeout( function() {
			$cell.css( {
				color:'AC' === submit.result ? 'green' : 'red',
			} ).addClass( 'verdict-done' );

			$marker.addClass( 'marker-done' );

			setTimeout( function() {
				$cell.remove();
				$marker.remove();
				def.resolve();
			}, 1125 )
		}, 125 );

		return def;
	}

	function slowAnimateRow(tm) {
		var i = teams().indexOf( tm ),
			j = sortTeams( teams().slice() ).indexOf( tm ),
			$row = $rows().eq( i ),
			height = $row.height();

		fixWidth( $row ); 
		$row.css( { top:0, display:'block' } );

		return rowUp( $row, i, i, j, -height );
	}

	function rowUp($row, initial, i, j, top) {
		return $.Deferred( function(def) {
			if (i === j) { 
				return def.resolve();//anyKey().done( function() {
					//def.resolve();
				//} ); 
			}

			var $next = $rows().eq( i-1 ),
				height = $next.height();

			fixWidth( $next );
			$next.css( { top:0, display:'block' } );

			setTimeout( function() {
				fastScroll().done( function() {
					$row.css( 'top', top + 'px' );
					$next.css( 'top', height + 'px' );

					var minMargin = -(Math.min( initial, 10 ) + 1) * height,
						nowMargin = Math.min( 0, parseInt( $el().css( 'marginTop' ) ) );
						
					var margin = revealMode()
						? Math.min( minMargin, nowMargin + height )
						: nowMargin + height;

					$el().css( 'marginTop', margin + 'px' );

					setTimeout( function() {
						rowUp( $row, initial, i-1, j, top-height ).done( function() {
							def.resolve();
						} );
					}, scrollTimeout() );
				} );
			}, 0 );
		} ).promise();
	}

	function fixWidth($row) {
		$row.find( 'td>span' ).each( function() {
			$( this ).css( 'width', $( this ).width() + 'px' );
		} );
	}

	function animateRow(tm) {
		var i = teams().indexOf( tm ),
			j = sortTeams( teams().slice() ).indexOf( tm ),
			$row = $rows().eq( i ),
			$nr = $row.clone(),
			height = $row.height(),
			to = (j+1) * height,
			from = (i+1) * height;

		var current = $row.hasClass( 'current' );
		$row.removeClass( 'current' );

		var def = $.Deferred();

		$nr.children().each( function(i) {
				$( this ).css( {
					width:$row.children().eq( i ).width() + 'px',
					backgroundColor:'white',
					opacity:0.75
				} );
			} ).end()
			.css( {
				position:'absolute',
				top:from + 'px',
				left:0,
				zIndex:999,
				'-webkit-transition':'top 2s ease, -webkit-transform 1s ease'
			} )
			.wrap( '<table></table>' ).closest( 'table' )
			.appendTo( $el() );

		if (0 === i%2) {
			$nr.addClass( 'even' );
		}

		setTimeout( function() {
			$nr.css( {
				top:to + 'px',
				'-webkit-transform':'scale(1.1)'
			} );

			0 === j%2
				? $nr.addClass( 'even' )
				: $nr.removeClass( 'even' );

			setTimeout( function() {
				$nr.css( '-webkit-transform', 'scale(1)' );
			}, 1000 );

			setTimeout( function() {
				$nr.remove();
				current && $rows().eq( j ).addClass( 'current' );
				def.resolve();
			}, 2250 )
		}, 0 );

		return def;
	}

	function scrollTo(tm) {
		var	rowIndex = teams().indexOf( tm ),
			$row = $rows().eq( rowIndex ),
			top = (rowIndex+1) * $row.height(),
			availHeight = $( window ).height(),
			height = $el().height(),
			current = parseInt( $el().css( 'marginTop' ) );

		var pos = Math.max( 0, top - availHeight / 2 );
		pos = Math.min( pos, height - availHeight + 128 );

		if (revealMode()) {
			pos = Math.max( pos, (Math.min( rowIndex, 10 ) + 1) * $row.height() );
		}

		$el().css( 'marginTop', -pos + 'px' );

		return $.Deferred( function(def) {
			if (current === -pos) {
				def.resolve(); return;
			}

			setTimeout( function() {
				def.resolve();
			}, scrollTimeout() );
		} ).promise();
	}

	function applySubmit(submit) {
		if (ok( submit )) {
			accepted( submit );
			return true;
		}
		
		if (!ce( submit )) {
			rejected( submit );
		}

		return false;
	}

	function visualize() {
		sortTeams( teams() );

		var id = $( '.current' ).attr( 'id' );
		$el().find( '#standing' ).remove();
		$el().append( renderHTML() );
		if (id) {
			$rows().filter( '#' + id ).addClass( 'current' );
		}
	}

	function renderHTML() {
		return template()( {
			list:teams().map( function(team, i) {
				var row = serialize( team );
				if (total( team ) > 0) {
					row.index = i < 30 ? i+1 : undefined;
				}
				return row;
			} ),
			letters:tasks_.map( function(task) {
				return task.letter;
			} )
		} );
	}

	function serialize(team) {
		return {
			id:team.id,
			nickname:team.nickname,
			tasks:tasks_.map( function(task) {
				return stringifyTask( team, task.letter );
			} ),
			total:total( team ),
			penalty:Math.floor( penalty( team ) / 60 )
		};
	}

	// todo: reduce
	function total(team) {
		var result = 0;
		$.each( team.tasks, function(index, task) {
			task > 0 && result++;
		} );

		return result;
	}

	// todo: reduce
	function penalty(team) {
		var result = 59;
		$.each( team.tasks, function(index, task) {
			if (task > 0) {
				result += Math.ceil( task%100000 ) 
					+ (Math.floor( task/100000 ) - 1) * 20 * 60;
			}
		} );

		return result;
	}

	function sortTeams(list) {
		return list.sort( function lowerThan(a, b) {
			if (total( a ) > total( b )) {
				return -1;
			} else if (total( a ) < total( b )) {
				return 1;
			} else if (penalty( a ) < penalty( b )) {
				return -1;
			} else if (penalty( a ) > penalty( b )) {
				return 1;
			} else if (penalty( a ) < penalty( b )) {
				return -1;
			} else if (a.nickname > b.nickname) {
				return 1;
			} else {
				return a.nickname < b.nickname ? -1 : 0;
			}
		} ); 
	}

	function stringifyTask(team, taskIndex) {
		var task = team.tasks[taskIndex];

		if (task > 0) {
			var amount = Math.floor( task / 100000 ) - 1;
			return '<span class="accepted">+' + (!amount ? '' : amount) 
				+ time( task )
				+ '</span>';
		}
			
		if (team.remaining( taskIndex ) > 0) {
			return '<span class="postponed">&times' + team.remaining( taskIndex ) + '</span>';
		}

		if (!task) {
			return '<span>.</span>';
		}

		return '<span class="rejected">' 
			+ Math.ceil( task / 100000 ) 
			+ time( task ) + '</span>';

		function time(task) {
			task = Math.abs( task );

			var secs = task%100000,
				hrs = Math.floor( secs / 3600 ),
				min = Math.floor( secs%3600 / 60 );

			(min < 10) && (min = '0' + min);

			return '<span class="time">' + hrs + ':' + min + '</span>';
		}
	}

	function ok(submit) {
		return 'AC' === submit.result;
	}

	function ce(submit) {
		return 'CE' === submit.result;
	}

	function frozen(submit) {
		return submit.time >= 3600*4;
	}

	function accepted(submit) {
		var tm = team( submit );
		if (tm.tasks[submit.task] > 0) {
			return;
		}

		var task = Math.abs( tm.tasks[submit.task] || 0 );
		task = Math.floor( task / 100000 );
		task = 1 + task;
		task *= 100000;
		tm.tasks[submit.task] = task + submit.time;
	}

	function rejected(submit) {
		var tm = team( submit );
		if (tm.tasks[submit.task] > 0) {
			return;
		}

		var task = Math.abs( tm.tasks[submit.task] || 0 );
		task = Math.floor( task / 100000 );
		task = 1 + task;
		task *= 100000;
		task += submit.time;
		tm.tasks[submit.task] = -task;
	}

	function postpone(submit) {
		team( submit ).postpone( submit );
	}

	function team(submit) {
		team.teams = team.teams || {};
		if (!team.teams[submit.userId]) {
			var remaining = {};
			team.teams[submit.userId] = {
				id:submit.userId,
				nickname:submit.nickname,
				tasks:{},
				info:submit.info,
				studyplace:submit.studyplace,
				city:submit.city,
				division:submit.division,
				submits:[],
				remaining:function(taskIndex) {
					return remaining[taskIndex] || 0;
				},
				shiftSubmit:function() {
					var submit = this.submits.shift();
					if (submit) {
						remaining[submit.task]--;
					}

					return submit;
				},
				postpone:function(submit) {
					this.submits.push( submit );
					remaining[submit.task] = remaining[submit.task] || 0;
					remaining[submit.task]++;
				}
			};	
		} 

		return team.teams[submit.userId];
	}

	function teams() {
		return teams._ = teams._ || $.map( team.teams, function(team) {
			return team;
		} );
	};

	function $el() {
		return $( '#mtable' ); 
	}

	function $rows() {
		return $el().find( '#standing tbody tr' );
	}

	function updateTime(time) {
		var hrs = Math.floor( time / 3600 ),
			min = Math.floor( (time%3600) / 60 ),
			sec = time%60;
		min < 10 && (min = '0' + min);
		sec < 10 && (sec = '0' + sec);

		$( '#time' ).html( hrs + ':' + min + ':' + sec );
	}

	function rowTemplate() {
		return rowTemplate._ = rowTemplate._ || Handlebars.compile( '<tr id="row{{id}}">\
			<td><span><span>{{index}}</span></span></td>\
			<td class="nickname"><span><span>{{nickname}}</span></span></td>\
			{{#tasks}}\
				<td class="task"><span>{{& .}}</span></td>\
			{{/tasks}}\
			<td><span><span>{{total}}</span></span></td>\
			<td><span><span>{{penalty}}</span></span></td>\
		</tr>' );
	}

	function template() {
		return template._ = template._ || Handlebars.compile( '\
			<table id="standing"><thead>\
				<th><span><span>#</span></span></th>\
				<th class="nickname"><span><span>Team<span></span></th>\
				{{#letters}}\
					<th><span><span>{{.}}</span></span></th>\
				{{/letters}}\
				<th><span><span>=</span></span></th>\
				<th><span><span>Penalty</span></span></th>\
			</thead><tbody>\
				{{#list}}<tr id="row{{id}}">\
					<td><span><span>{{index}}</span></span></td>\
					<td class="nickname"><span><span>{{nickname}}</span></span></td>\
					{{#tasks}}\
						<td class="task"><span>{{& .}}</span></td>\
					{{/tasks}}\
					<td><span><span>{{total}}</span></span></td>\
					<td><span><span>{{penalty}}</span></span></td>\
				</tr>{{/list}}\
			</tbody></table>\
		' );
	}

	function slowScroll() {
		return $.Deferred( function(def) {
			$( document.body ).removeClass( 'fast-scroll turtle-scroll' ).addClass( 'slow-scroll' );
			setTimeout( function() {
				def.resolve();
			}, 0 );
		} ).promise();
	}

	function fastScroll() {
		return $.Deferred( function(def) {
			$( document.body ).removeClass( 'slow-scroll turtle-scroll' ).addClass( 'fast-scroll' );
			setTimeout( function() {
				def.resolve();
			}, 0 );
		} ).promise();
	}

	function turtleScroll() {
		return $.Deferred( function(def) {
			$( document.body ).addClass( 'fast-scroll slow-scroll' ).addClass( 'turtle-scroll' );
			setTimeout( function() {
				def.resolve();
			}, 0 );
		} ).promise();
	}

	function scrollTimeout() {
		if ($( document.body ).hasClass( 'fast-scroll' )) {
			return 125;
		} else if ($( document.body ).hasClass( 'slow-scroll' )) {
			return 1125;
		} else if ($( document.body ).hasClass( 'turtle-scroll' )) {
			return 25250;
		}

		return 0;
	}

})( jQuery, xicl = window.xicl || {} );
