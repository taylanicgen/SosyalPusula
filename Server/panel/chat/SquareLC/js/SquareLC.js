// Global variables
var SquareLC, initialized = false, timed_out = false, online_count = 0;

var $window, $container, $top, $title, $count, $lines, $online, $send, $input, $button;

// Compensation
if(!Array.prototype.indexOf)
{
	Array.prototype.indexOf = function(element)
	{
		var i;
		
		for(i=0; i<this.length; i++)
		{
			if(this[i] === element)
			{
				return i;
			}
		}
		
		return -1;
	};
}

// Time-out
function time_out()
{
	events.error({'message': 'time_out'});
	
	timed_out = true;
}

// Append
function append(html)
{
	var scroll = $lines[0].scrollHeight - $lines.scrollTop() === $lines.innerHeight();
	
	$lines.append(html);
	
	if(scroll)
	{
		$lines.scrollTop($lines[0].scrollHeight);
	}
}

// User
function User(data, online)
{
	this.data = data;
	this.online = (typeof online === 'undefined')?true:online;
	
	this.name = data.name;
	this.level = data.level;
	this.session = data.session;
}

User.prototype.get = function(list)
{
	var html = '<a class="user-level-'+this.level, key, href = '';
	
	if(this.session === session)
	{
		html += ' self';
	}
	
	html += '"';
	
	if(typeof this.data === 'object' && 'id' in this.data && config.profile_path)
	{
		html += 'href="'+config.profile_path.replace(/{id}/g, this.data.id).replace(/{name}/g, this.name)+'" target="_parent"';
	} else if(this.session !== session)
	{
		html += 'href="javascript: void(0);" onclick="whisper(\''+this.name+'\')"';
	}
	
	if(list)
	{
		html += ' id="user-'+this.session+'"';
	}
	
	html += '>'+this.name+'</a>';
	
	return html;
};

User.prototype.commands = function()
{
	var response = [], key;
	
	for(key in config.commands)
	{
		if(config.commands[key] !== false && this.level >= config.commands[key] && key !== 'message')
		{
			response.push(key);
		}
	}
	
	if('lock' in this.data)
	{
		if(key = $.inArray('nickname', response))
		{
			response.splice(key, 1);
		}
	}
	
	return (response.length === 1)?[]:response;
};

function load_user(id)
{
	if(!(id in users))
	{
		users[id] = new User(parse_line($.ajax(
		{
			'url': 'user.php',
			'data': {'channel': channel, 'session': id},
			'async': false,
			'cache': false,
			'dataType': 'text'
		}).responseText), false);
	}
	
	return users[id];
}

function whisper(name)
{
	var content = $input.val();
	
	if(content.substr(0, 9).toLowerCase() === '/whisper ')
	{
		if(content.split(' ').length > 2)
		{
			content = content.split(' ').slice(2).join(' ');
		} else
		{
			content = '';
		}
	}
	
	$input.val('/whisper '+name+' '+content).focus();
}

// Compare nickname
function cmp_nickname(a, b)
{
	return config.name_case_sensitive?(a === b):(a.toLowerCase() === b.toLowerCase());
}

// User online?
function user_online(name)
{
	var key;
	
	for(key in users)
	{
		if(key === session)
		{
			continue;
		}
		
		if(cmp_nickname(name, users[key].name))
		{
			return users[key].online?users[key]:false;
		}
	}
	
	return false;
}

// Language
function __(data)
{
	var response = lang[data.message], key;
	
	if(typeof response === 'string')
	{
		for(key in data)
		{
			if(key !== 'message')
			{
				response = response.replace('{'+key+'}', data[key]);
			}
		}
		
		return response;
	}
	
	return (data.n in response)?response[data.n]:response['n'].replace('{n}', data.n);
}

// Period in seconds
function period_seconds(string)
{
	var ts = new Date().getTime(), key, length;
	
	if(typeof string === 'undefined')
	{
		return 0;
	}
	
	var periods = string.match(/([0-9]+)( )?([a-z]+)/gi);
	
	if(periods)
	{
		for(key=0; key<periods.length; key++)
		{
			for(length in lang.time_periods)
			{
				if($.inArray(periods[key].replace(/[ 0-9]+/g, ''), lang.time_periods[length]) !== -1)
				{
					ts += parseInt(periods[key])*length*1000;
					
					break;
				}
			}
		}
	} else
	{
		ts += parseInt(string)*1000;
	}
		
	return ts;
}

// Events
var events = {};

// Info
events.info = function(data, escape_html)
{
	var message = __(data);
	
	if(typeof escape_html !== 'undefined')
	{
		message = message.replace(/>/g, '&gt;').replace(/</g, '&lt;');
	}
	
	append('<p class="info">'+message+'</p>');
};

// Error
events.error = function(data)
{
	append('<p class="error">'+__(data)+'</p>');
	
	return false;
};

// List
events.list = function(data)
{
	var html, key;
	
	html = '<table class="list">';
	
	for(key in data)
	{
		html += '<tr><th>'+key+'</th><td>'+data[key]+'</td></tr>';
	}
	
	html += '</table>';
	
	append(html);
};

// Join
events.join = function(data)
{
	if(!initialized || data.session === session)
	{
		return;
	}
	
	users[data.session] = new User(data, true);
	
	online_count++;
	
	$count.html(__(
	{
		'message': 'online_users',
		'n': online_count
	}));
	
	if(SquareLC.view_online)
	{
		$online.append(users[data.session].get(true));
	}
	
	events.info(
	{
		'message': 'user_join',
		'user': load_user(data.session).get()
	});
	
	data = load_user(data.session).data;
	
	if(typeof data === 'object' && 'muted' in data)
	{
		events.info(
		{
			'message': 'user_is_muted',
			'user': load_user(data.session).get()
		});
	}
};

// Leave
events.leave = function(data)
{
	if(initialized)
	{
		if(data.session === session)
		{
			return time_out();
		}
		
		users[data.session].online = false;
		
		online_count--;
		
		$count.html(__(
		{
			'message': 'online_users',
			'n': online_count
		}));
		
		if(SquareLC.view_online)
		{
			$('#user-'+data.session).remove();
		}
		
		if(data.session !== session)
		{
			events.info(
			{
				'message': 'user_leave',
				'user': load_user(data.session).get()
			});
		}
	}
};

// Message
events.message = function(data, action)
{
	var date = new Date(parseInt(last_ts)), hours = date.getHours(), minutes = date.getMinutes(), key, i, message;
	
	if(hours < 10)
	{
		hours = '0'+hours;
	}
	
	if(minutes < 10)
	{
		minutes = '0'+minutes;
	}
	
	message = data.message.replace(/\\/g, '').replace(/</g, '&lt;').replace(/>/g, '&gt;');
	
	if('censor' in lang)
	{
		for(i=0; i<lang.censor.length; i++)
		{
			message = message.replace(new RegExp(lang.censor[i], 'gi'), new Array(lang.censor[i].length+1).join('*'));
		}
	}
	
	if(users[session].commands().indexOf('emotes') !== -1)
	{
		for(key in config.emoticons)
		{
			for(i=0; i<config.emoticons[key].length; i++)
			{
				message = message.replace(new RegExp(config.emoticons[key][i].replace(/[[\]{}()*+?.\\|^$\-,&#\s]/g, '\\$&'), 'g'), '<img src="../emoticon/'+key+'.png" alt=""/>');
			}
		}
	}
	
	message = message.replace(/(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig, '<a target="_parent" href="$1">$1</a>');
	
	append('<p class="message"><span class="time">'+hours+':'+minutes+'</span> <span class="user">'+load_user(data.session).get()+((typeof action === 'undefined')?'':(' '+lang['action_'+action]))+'</span>: '+message)+'</p>';
};

// Nickname change
events.nickname_change = function(data)
{
	var user = new User(data, true);
	
	events.info(
	{
		'message': 'nickname_change',
		'user': data.previous_nickname,
		'new': user.get()
	});
	
	if(initialized)
	{
		users[data.session] = user;
		
		if(SquareLC.view_online)
		{
			$('#user-'+data.session).replaceWith(user.get(true));
		}
	}
};

// Whisper
events.whisper = function(data)
{
	if(data.to === session || data.session === session)
	{
		events.message(data, 'whisper');
	}
};

// Moderate
events.moderate = function(action, data)
{
	var current, target, period;
	
	if(data.time === '0')
	{
		events.info(
		{
			'message': 'action_'+action+'_by',
			'user': load_user(data.user).get(),
			'moderator': load_user(data.moderator).get()
		});
	} else
	{
		current = new Date();
		target = new Date(parseInt(data.time));
		
		if(current.toLocaleDateString() === target.toLocaleDateString())
		{
			period = target.toLocaleTimeString();
		} else
		{
			period = target.toLocaleDateString();
		}
		
		events.info(
		{
			'message': 'action_'+action+'_time',
			'user': load_user(data.user).get(),
			'moderator': load_user(data.moderator).get(),
			'time': period
		});
	}
};

// Un-Moderate
events.un_moderate = function(action, data)
{
	if('moderator' in data)
	{
		events.info(
		{
			'message': 'action_un'+action+'_by',
			'user': load_user(data.user).get(),
			'moderator': load_user(data.moderator).get()
		});
	} else
	{
		events.info(
		{
			'message': 'action_un'+action,
			'user': load_user(data.user).get()
		});
	}
};

// Mute
events.mute = function(data)
{
	var current, target, period;
	
	if(initialized)
	{
		if(data.user === session)
		{
			$input.attr('disabled', 'disabled').val(lang.you_are_muted);
		}
		
		users[data.user].data.muted = data.time;
	}
	
	events.moderate('mute', data);
};

// Unmute
events.unmute = function(data)
{
	if(initialized && data.user in users)
	{
		if(data.user === session)
		{
			$input.removeAttr('disabled').val('').focus();
		}
		
		delete users[data.user].data.muted;
	}
	
	events.un_moderate('mute', data);
};

// Ban
events.ban = function(data)
{
	var current, target, period;
	
	if(initialized)
	{
		if(data.user === session)
		{
			timed_out = true;
			
			$input.attr('disabled', 'disabled').val(lang.you_are_banned);
		}
		
		users[data.user].data.ban = data.time;
	}
	
	events.moderate('ban', data);
};

// Unban
events.unban = function(data)
{
	if(initialized && data.user in users)
	{
		delete users[data.user].data.ban;
	}
	
	events.un_moderate('ban', data);
};

// Set
events.set_user_var = function(data)
{
	if(data.session in users)
	{
		users[data.session].data[data.key] = data.value;
	}
};

// Commands
commands = {};

// Help
commands.help = function(command)
{
	var key, list = {}, available = users[session].commands();
	
	if(typeof command === 'undefined')
	{
		events.info({'message': 'command_list'});
		
		for(key=0; key<available.length; key++)
		{
			list['/'+available[key]] = lang.commands[available[key]];
		}
		
		$input.val('').focus();
	} else
	{
		if($.inArray(command, available) === -1)
		{
			return events.error({'message': 'command_not_found'});
		}
		
		events.info({'message': 'command_args'}, true);
		events.info({'message': 'command_usage', 'command': '/'+command+' '+lang.commands_help[command].format}, true);
		
		for(key in lang.commands_help[command])
		{
			if(key !== 'format')
			{
				list[key] = lang.commands_help[command][key];
			}
		}
		
		$input.val('/'+command).focus();
	}
	
	events.list(list);
	
	return false;
};

// Nickname
commands.nickname = function(name)
{
	// Current
	if(name === users[session].name)
	{
		return events.error({'message': 'nickname_already'});
	}
	
	// Length
	if(name.length < config.name_min_length || name.length > config.name_max_length)
	{
		return events.error({'message': 'nickname_length', 'min': config.name_min_length, 'max': config.name_max_length});
	}
	
	// Alphanumeric
	if(!name.replace(/\w+/, '').match(new RegExp('^['+lang.alphanumeric+']*$')))
	{
		return events.error({'message': 'nickname_alphanumeric'});
	}
	
	// Online
	if(user_online(name) !== false)
	{
		return events.error({'message': 'nickname_in_use'});
	}
	
	return true;
};

// Whisper
commands.whisper = function(name, message)
{
	// Self
	if(cmp_nickname(name, users[session].name))
	{
		return events.error({'message': 'cannot_whisper_self'});
	}
	
	// User online
	var user = user_online(name);
	
	if(user === false)
	{
		return events.error({'message': 'nickname_not_found'});
	}
	
	return [user.session, message];
};

// Mute
commands.mute = function(name, time)
{
	// Self
	if(cmp_nickname(name, users[session].name))
	{
		return events.error({'message': 'cannot_mute_self'});
	}
	
	// User online
	var user = user_online(name);
	
	if(user === false)
	{
		return events.error({'message': 'nickname_not_found'});
	}
	
	// Higher level
	if(users[session].level <= user.level)
	{
		return events.error({'message': 'cannot_mute'});
	}
	
	return [user.session, period_seconds(time)];
};

// Unmute
commands.unmute = function(name)
{
	// User online
	var user = user_online(name);
	
	if(user === false)
	{
		return events.error({'message': 'nickname_not_found'});
	}
	
	// Not muted
	if(!('muted' in user.data))
	{
		return events.error({'message': 'user_not_muted'});
	}
	
	return [user.session];
};

// Ban
commands.ban = function(name, time)
{
	// Self
	if(cmp_nickname(name, users[session].name))
	{
		return events.error({'message': 'cannot_ban_self'});
	}
	
	// User online
	var user = user_online(name);
	
	if(user === false)
	{
		return events.error({'message': 'nickname_not_found'});
	}
	
	// Higher level
	if(users[session].level <= user.level)
	{
		return events.error({'message': 'cannot_ban'});
	}
	
	return [user.session, period_seconds(time)];
};

// Unban
commands.unban = function(name)
{
	// User online
	var user = user_online(name);
	
	if(user === false)
	{
		return events.error({'message': 'nickname_not_found'});
	}
	
	// Not banned
	if(!('ban' in user.data))
	{
		return events.error({'message': 'user_not_banned'});
	}
	
	return [user.session];
};

// Emotes
commands.emotes = function()
{
	var key, emotes = {};
	
	for(key in config.emoticons)
	{
		emotes['<img src="../emoticon/'+key+'.png" alt="" style="float: left; margin-right: 5px;"/> '+key] = config.emoticons[key].join(' or ');
	}
	
	events.list(emotes);
	
	$input.val('');
	
	return false;
};

// Set
commands.set = function(name, key, value)
{
	// User online
	var user = user_online(name);
	
	if(user === false)
	{
		return events.error({'message': 'nickname_not_found'});
	}
	
	return [user.session, key, value];
};

// Get
commands.get = function(name, key)
{
	// User online
	var user = user_online(name);
	
	if(user === false)
	{
		return events.error({'message': 'nickname_not_found'});
	}
	
	console_msg(user.data[key]);
	
	$input.val('').focus();
	
	return false;
};

// Parse line
function parse_line(line, event)
{
	var key, data = {}, key_max;
	
	line = line.split(PHP_SEP);
	
	if(line.length === 1)
	{
		return false;
	}
	
	event = event?1:0;
	
	for(key=0; key<line.length/2-event; key++)
	{
		data[line[key+event*2]] = line[key+event+line.length/2];
	}
	
	return event?{'ts': line[0], 'event': line[1], 'data': data}:data;
}

// Parse lines
function parse_lines(response)
{
	var i, lines, line, data, key, valid = true;
	
	lines = response.split(PHP_EOL);
	
	for(i=0; i<lines.length; i++)
	{
		if(line = parse_line(lines[i], true))
		{
			if(initialized && line.ts <= last_ts)
			{
				continue;
			}
			
			last_ts = line.ts;
			
			events[line.event](line.data);
			
			if(line.event === 'error')
			{
				valid = false;
			}
		}
	}
	
	return valid;
}

// Load lines
function load_lines()
{
	setTimeout(function()
	{
		if(timed_out)
		{
			return;
		}
		
		$.ajax('../db/lines/'+channel+'.csv',
		{
			'cache': false,
			'dataType': 'text',
			'error': function(xhr)
			{
				if(xhr.status === 404)
				{
					time_out();
				} else
				{
					load_lines();
				}
			},
			'success': function(response)
			{
				if(typeof response !== 'undefined')
				{
					parse_lines(response);
				}
				
				load_lines();
			},
			'timeout': config.load_lines
		});
		
	}, config.load_lines);
}

// Stay online
function load_online()
{
	setTimeout(function()
	{
		if(timed_out)
		{
			return;
		}
		
		$.ajax('online.php',
		{
			'cache': false,
			'data': {'channel': channel},
			'error': function(xhr)
			{
				if(xhr.status === 404)
				{
					time_out();
				} else
				{
					load_online();
				}
			},
			'success': function()
			{
				load_online();
			}
		});
	}, config.load_online);
}

// Console
function console_msg(message)
{
	append('<p class="console">'+message+'</p>');
}

// Send
function send(e)
{
	// Event
	e.preventDefault();
	
	// Muted
	if('muted' in users[session].data)
	{
		return false;
	}
	
	// Variables
	var ts = new Date().getTime();
	var input = $input.val();
	
	// Validation
	if(input === '' || ts - last_send < config.send_timeout)
	{
		return false;
	} else
	{
		last_send = ts;
	}
	
	// Command
	var data, command, format, args, key, response;
	
	var user_commands = users[session].commands();
	
	if(user_commands.length && input.substr(0, 1) === '/')
	{
		data = input.substr(1).split(' ');
		command = data[0];
		
		// Validate
		response = users[session].commands();
		
		if($.inArray(command, user_commands) === -1)
		{
			return events.error({'message': 'command_not_found'});
		}
		
		// Format
		if(lang.commands_help[command].format !== '')
		{
			format = '^';
			
			args = lang.commands_help[command].format.match(/(\<|\[)([a-z]+)(\>|\])/g);
			
			for(key=0; key<args.length; key++)
			{
				if(key === args.length - 1)
				{
					format += key?'( .+)':'(.+)';
				} else if(key === 0)
				{
					format += '([^ ]+)';
				} else
				{
					format += '( [^ ]+)';
				}
				
				if(args[key].substr(0, 1) === '[')
				{
					format += '?';
				}
			}
			
			format += '$';
			
			format = new RegExp(format);
			
			// Arguments
			args = data.slice(1).join(' ');
			
			if((response = args.match(format)) === null)
			{
				return events.error({'message': 'command_format', 'command': command});
			}
			
			args = response.slice(1);
			
			if(args.length)
			{
				for(key=0; key<args.length; key++)
				{
					if(key && typeof(args[key]) !== 'undefined')
					{
						args[key] = args[key].substr(1);
					}
					
					if(args[key] === '')
					{
						delete args[key];
					}
				}
			}
		} else
		{
			args = [];
		}
		
		if(command in commands)
		{
			response = commands[command].apply(this, args);
		} else
		{
			response = true;
		}
		
		if(response === false)
		{
			return false;
		}
		
		if(response !== true)
		{
			args = response;
		}
	} else
	{
		args = [input];
		
		command = 'message';
	}
	
	// Request
	response =
	{
		'channel': channel,
		'command': command,
		'args': 0
	};
	
	for(key=0; key<args.length; key++)
	{
		if(typeof(args[key]) !== 'undefined')
		{
			response.args++;
			
			response['arg'+key] = args[key];
		}
	}
	
	response = $.ajax('send.php',
	{
		'type': 'POST',
		'async': false,
		'data': response,
		'dataType': 'text'
	}).responseText;
	
	// Return
	if(parse_lines(response))
	{
		$input.val('').focus();
	}
	
	return false;
}

// Layout
function layout()
{
	var width, height, top, left;
	
	// Container
	$container.css(
	{
		'width': $window.width() - $container.outerWidth() + $container.width(),
		'height': $window.height() - $container.outerHeight() + $container.height()
	});
	
	// Online
	top = 0;
	height = $container.innerHeight() - $send.outerHeight(true) - $online.outerHeight() + $online.height();
	
	if(SquareLC.title)
	{
		top += $top.outerHeight(true);
		height -= $top.outerHeight(true);
	}
	
	$online.css(
	{
		'top': top,
		'right': 0,
		
		'height': height,
		'max-height': height
	});
	
	// Lines
	width = $container.width() - $lines.outerWidth() + $lines.width();
	height = $container.innerHeight() - $send.outerHeight(true) - $lines.outerHeight() + $lines.height();
	
	if(SquareLC.view_online)
	{
		width -= $online.outerWidth(true);
	}
	
	if(SquareLC.title)
	{
		height -= $top.outerHeight(true);
	}
	
	$lines.css(
	{
		'width': width,
		'max-width': width,
		
		'height': height,
		'max-height': height
	});
	
	// Send
	$send.css(
	{
		'width': $container.width() - $send.outerWidth(true) + $send.width()
	});
	
	// Input
	width = $send.width();
	
	if(SquareLC.view_send_btn)
	{
		width -= $button.outerWidth(true);
	}
	
	$input.css(
	{
		'width': width
	});
}

// Initialize
$(function()
{
	// Parent
	SquareLC = parent['SquareLC_'+channel];
	
	// Container
	$container = $('#container');
	
	if(SquareLC.outer_border)
	{
		$container.addClass('border');
	} else
	{
		$container.addClass('no-border');
	}
	
	// Top
	$top = $('#top');
	
	if(SquareLC.title)
	{
		$top.show();
	}
	
	// Title
	$title = $('#title').html(SquareLC.title);
	
	// Users
	var key;
	
	for(key in users)
	{
		online_count++;
		
		users[key] = new User(users[key], true);
	}
	
	// Count
	$count = $('#count').html(__(
	{
		'message': 'online_users',
		'n': online_count
	}));
	
	// Lines
	$lines = $('#lines').html('');
	
	// Cookies
	if(cookie !== false && document.cookie.indexOf('SquareLC') === -1)
	{
		events.error({'message': 'nocookie'});
		
		$.get('nocookie.php', {'cookie': cookie});
		
		return;
	}
	
	// Online
	var user;
	
	$online = $('#online');
	
	if(SquareLC.view_online)
	{
		$online.show();
		
		for(key in users)
		{
			$online.append($(users[key].get(true)));
		}
	}
	
	// Send
	$send = $('#send').show().submit(send);
	
	// Input
	$input = $('#input');
	
	if('muted' in users[session].data)
	{
		$input.attr('disabled', 'disabled').val(lang.you_are_muted);
	} else
	{
		$input.focus();
	}
	
	// Button
	$button = $('#button');
	
	if(SquareLC.view_send_btn)
	{
		$button.show().hover(function()
		{
			$button.addClass('hover');
		}, function()
		{
			$button.removeClass('hover');
		});
		
		$container.addClass('button');
	}
	
	// Layout
	$window = $(window).resize(layout);
	
	layout();
	
	// Log
	if('welcome' in SquareLC)
	{
		append('<p class="info">'+SquareLC.welcome+'</p>');
	} else
	{
		events.info({'message': 'welcome'});
	}
	
	parse_lines(log);
	
	// Initialize
	initialized = true;
	
	load_lines();
	load_online();
});