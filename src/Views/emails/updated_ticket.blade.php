<?php 
	$notification_owner = unserialize($notification_owner);
	$ticket = unserialize($ticket);
	$original_ticket = unserialize($original_ticket);
	$email_from = unserialize($email_from);
?>

@extends($email)

@section('content')
	@if($notification_type == 'close')
		<p>{!! trans('panichd::email/globals.closed_ticket', ['user' => $notification_owner->name]) !!}</p>
	@elseif($notification_type == 'status')
		<p>{!! trans('panichd::email/globals.updated_status', ['user' => $notification_owner->name]) !!}</p>
	@elseif ($notification_type == 'agent')
		<p>{!! trans('panichd::email/globals.updated_agent', ['user' => $notification_owner->name]) !!}</p>
	@endif
	
	@if ($recipient->levelInCategory($ticket->category->id) > 1)
		@include('panichd::emails.partial.common_fields')
		@include('panichd::emails.partial.both_html_fields')
	@elseif ( $original_ticket->isComplete() != $ticket->isComplete() || $original_ticket->status->id != $ticket->status->id)
		@if (is_object($original_ticket) && is_object($original_ticket->owner) && is_object($ticket) && is_object($ticket->owner))
			<ul><li>
				<b>{{ trans('panichd::lang.list') . trans('panichd::lang.colon') }}</b>
				@if($original_ticket->isComplete() != $ticket->isComplete())
					<strike>{{ trans('panichd::lang.'.($original_ticket->isComplete() ? 'complete-tickets-adjective' : 'active-tickets-adjective')) }}</strike> 
				@endif
				<span>{{ trans('panichd::lang.'.($ticket->isComplete() ? 'complete-tickets-adjective' : 'active-tickets-adjective')) }}</span>
			</li><li>
				<b>{{ trans('panichd::lang.status') . trans('panichd::lang.colon') }}</b>
				@if($original_ticket->status->id != $ticket->status->id)
					<strike>{{ $original_ticket->status->name }}</strike> 
				@endif
				<span style="color: {{ $ticket->status->color }};">{{ $ticket->status->name }}</span>
			</li></ul>
		@endif
	@endif
@stop