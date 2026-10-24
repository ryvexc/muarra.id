@extends("layout.admin.main")

@section('css')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Mono:ital,wght@0,300;0,400;0,500;1,300;1,400;1,500&display=swap" rel="stylesheet">

  <style>
    .journal-terminal,
.journal-terminal *{
    font-family:"DM Mono", monospace;
}

    /* JetBrains Mono */
.journal-terminal{
    background:#11141a;
    border:1px solid var(--border);
    border-radius:14px;
    overflow:hidden;
}

.journal-line{
    display:grid;
    grid-template-columns:120px 90px 1fr 90px;
    gap:18px;
    /* align-items:center; */

    padding:10px 16px;

    font-family:"JetBrains Mono", monospace !important;
    font-size:12px;

    border-bottom:1px solid rgba(255,255,255,.04);

    transition:.15s;
}

.journal-line:last-child{
    border-bottom:none;
}

.journal-line:hover{
    background:var(--row-hover);
}

.col-id{
    color:#94a3b8;
    cursor:pointer;
}

.col-id:hover{
    color:white;
}

.col-event{
    color:#e2e8f0;
    font-weight:500;
}

.col-time{
    color:#64748b;
    text-align:right;
}

/* Severity */

.info .col-level{
    color:#60a5fa;
}

.success .col-level{
    color:#4ade80;
}

.warning .col-level{
    color:#fbbf24;
}

.danger .col-level{
    color:#f87171;
}

.col-message{
    white-space: pre-wrap; /* atau pre kalau gak mau wrap */
}

/* Mobile */

@media(max-width:768px){

    .journal-line{
        grid-template-columns:1fr;
        gap:4px;
    }

    .col-time{
        text-align:left;
    }

}
  </style>
@endsection

@section("content")
    <section>
        <div class="page-header">
            <div>
                <h1>Event Journal</h1>
                {{-- <p>Manage your ingredients/stocks.</p> --}}
            </div>
            
            {{-- <button class="btn-primary open-modal" data-modal="addModal">
                <i class="fa-solid fa-plus"></i>
                New Ingredient
            </button> --}}
        </div>

        <div class="journal-terminal">

    @foreach ($logs as $log)
        <div class="journal-line info">
          <span class="col-id">EVT-000124</span>
          <span class="col-level">INFO</span>
          <span class="col-event">
            <div>
              <p class="col-message" style="color: white">{{ strtoupper($log['type']) }}</p>
              <p class="col-message" style="color: white">{{ strtoupper($log['message']) }}</p>
            </div>
          </span>
          <span class="col-time">08:05:12</span>
      </div>
    @endforeach
    

    {{-- <div class="journal-line warning">
        <span class="col-id">EVT-000125</span>
        <span class="col-level">WARNING</span>
        <span class="col-event">STOCK_ADJUSTED</span>
        <span class="col-time">16:10:44</span>
    </div>

    <div class="journal-line success">
        <span class="col-id">EVT-000126</span>
        <span class="col-level">SUCCESS</span>
        <span class="col-event">ORDER_COMPLETED</span>
        <span class="col-time">16:12:01</span>
    </div> --}}

</div>
      </div>
    </section>
@endsection

@section("script")
    
@endsection