
@if ($type == 'Provision')
    <a href="{{ route('importDetail.index') }}" style="color: inherit">Unapprove Provision Pending</a>
@elseif ($type == 'Journal')
    <a href="{{ route('journal-entries.index') }}" style="color: inherit">Unapprove Entries Pending</a>
@elseif($type == "Import")
    <a href="{{ route('summaries.index') }}" style="color: inherit">Unapprove Import Pending</a>
@elseif($type == "Posting")
    <a href="{{ route('approveEntry.index') }}" style="color: inherit">Unapprove Journal Entries Pending</a>
@endif

