@extends('layouts.app')

@section('title', __('backend.platform_calendar.page_title'))

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<div class="container-fluid px-0">
    <h1 class="text-center mb-4" style="font-weight:800; font-size:32px;">{{ __('backend.platform_calendar.heading') }}</h1>

    <div class="d-flex justify-content-between align-items-center mb-3 px-3">
        <div class="d-flex gap-2">
            <button id="monthViewBtn" class="btn btn-outline-secondary btn-sm">{{ __('backend.platform_calendar.month') }}</button>
            <button id="weekViewBtn" class="btn btn-outline-secondary btn-sm">{{ __('backend.platform_calendar.week') }}</button>
            <button id="dayViewBtn" class="btn btn-outline-secondary btn-sm">{{ __('backend.platform_calendar.day') }}</button>
        </div>

        <div>
            <span id="currentViewLabel" class="h5 fw-bold"></span>
        </div>

        <div class="d-flex gap-2">
            <button id="prevBtn" class="btn btn-outline-primary">&lt; {{ __('backend.platform_calendar.previous') }}</button>
            <button id="nextBtn" class="btn btn-outline-primary">{{ __('backend.platform_calendar.next') }} &gt;</button>
        </div>
    </div>

    <div class="calendar-container border shadow-sm mx-3" style="background:#fff; min-height:70vh;">
        <div id="calendarContent"></div>
    </div>

    <div class="modal fade" id="modal-view-event" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <h4>{{ __('backend.platform_calendar.events_on') }} <span id="eventDateTitle"></span></h4>
                    <div id="modalEventsList"></div>
                    <div id="modalEventsControls" class="mt-2"></div>
                </div>
                <div class="modal-footer">
                    <button id="openAddEvent" class="btn btn-primary">{{ __('backend.platform_calendar.add_event') }}</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('backend.platform_calendar.close') }}</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-view-event-add" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="add-event-form">
                    <div class="modal-body">
                        <h4>{{ __('backend.platform_calendar.add_event') }}</h4>
                        <input name="title" class="form-control mb-2" placeholder="{{ __('backend.platform_calendar.title') }}" required>
                        <textarea name="description" class="form-control mb-2" placeholder="{{ __('backend.platform_calendar.description') }}"></textarea>
                        <select name="color" class="form-control mb-2">
                            <option value="blue">{{ __('backend.platform_calendar.colors.blue') }}</option>
                            <option value="green">{{ __('backend.platform_calendar.colors.green') }}</option>
                            <option value="red">{{ __('backend.platform_calendar.colors.red') }}</option>
                        </select>
                        <select name="icon" class="form-control">
                            <option value="calendar">{{ __('backend.platform_calendar.icons.calendar') }}</option>
                            <option value="group">{{ __('backend.platform_calendar.icons.group') }}</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" type="submit">{{ __('backend.platform_calendar.save') }}</button>
                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">{{ __('backend.platform_calendar.close') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.calendar-container {
    width: 100%;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    color: #333;
}
.calendar-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    padding: 10px 15px;
    border-radius: 12px;
    background-color: #f8f9fa;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}
.calendar-header .view-buttons {
    display: flex;
    gap: 8px;
}
.calendar-header .view-buttons button {
    font-size: 0.85rem;
    padding: 5px 14px;
    border-radius: 8px;
    border-width: 1.5px;
    transition: all 0.2s ease;
}
.calendar-header .view-buttons button:hover {
    transform: translateY(-2px);
    box-shadow: 0 3px 6px rgba(0,0,0,0.1);
}
.calendar-header .view-buttons button.active {
    background: #0e4984;
    border-color: #061523;
    color: #fff;
}
#currentViewLabel {
    font-size: 1.25rem;
    font-weight: 600;
    text-align: center;
    color: #333;
}
.calendar-header .nav-buttons {
    display: flex;
    gap: 8px;
}
.calendar-header .nav-buttons button {
    font-size: 0.85rem;
    padding: 5px 14px;
    border-radius: 8px;
    transition: all 0.2s ease;
}
.calendar-header .nav-buttons button:hover {
    transform: translateY(-2px);
    box-shadow: 0 3px 6px rgba(0,0,0,0.1);
}
#deleteEventsBtn {
    background-color: #dc3545;
    color: #fff;
    border: none;
}
#deleteEventsBtn:hover {
    background-color: #c82333;
}
.weekdays {
    display: flex;
    gap: 2px;
    margin-bottom: 5px;
}
.weekdays div {
    flex: 1;
    background-color: #1a548d;
    color: #fff;
    font-weight: 600;
    text-align: center;
    padding: 10px 0;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}
.weekdays div:hover {
    background-color: #66b3ff;
    transform: translateY(-1px);
}
.day-cell {
    flex: 1 0 14.28%;
    border-radius: 10px;
    text-align: center;
    padding: 10px;
    font-weight: 600;
    box-sizing: border-box;
    min-height: 100px;
    background: #fafafa;
    border: 1px solid #e0e0e0;
    transition: all 0.3s ease;
    position: relative;
    cursor: pointer;
}
.day-cell.today {
    background-color: #a6469c;
    border: 2px solid #3399ff;
}
.day-cell:hover {
    background-color: #f0f8ff;
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    transform: translateY(-2px);
}
.event {
    font-size: 13px;
    margin-top: 5px;
    padding: 6px 10px;
    border-radius: 12px;
    color: #fff;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    cursor: pointer;
    box-shadow: 1px 1px 4px rgba(0,0,0,0.2);
    transition: all 0.3s ease;
}
.event:hover {
    transform: scale(1.08);
    opacity: 0.95;
}
.event.blue { background: linear-gradient(135deg,#3399ff,#66ccff); }
.event.green { background: linear-gradient(135deg,#28a745,#71e175); }
.event.red { background: linear-gradient(135deg,#dc3545,#ff7f7f); }
.week-row {
    display: flex;
    margin-bottom: 5px;
}
.week-row .day-cell {
    flex: 1;
    margin: 2px;
    min-height: 120px;
    border-radius: 10px;
    padding: 8px;
    background: #b7cdf3;
    border: 1px solid #e0e0e0;
}
.day-view {
    display: flex;
    flex-direction: column;
    gap: 10px;
}
.day-view .day-cell {
    width: 100%;
    min-height: 80px;
    border-radius: 10px;
    padding: 10px;
    background: #ffffff;
    box-shadow: 0 2px 4px rgba(0,0,0,0.08);
}
.modal-backdrop { display: none !important; }
.tooltip-inner { max-width: 250px; text-align: left; font-size: 0.9rem; }
h4,h5 { font-weight: 700; color: #222; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function(){
const months = [
    @json(__('backend.platform_calendar.month_names.january')),
    @json(__('backend.platform_calendar.month_names.february')),
    @json(__('backend.platform_calendar.month_names.march')),
    @json(__('backend.platform_calendar.month_names.april')),
    @json(__('backend.platform_calendar.month_names.may')),
    @json(__('backend.platform_calendar.month_names.june')),
    @json(__('backend.platform_calendar.month_names.july')),
    @json(__('backend.platform_calendar.month_names.august')),
    @json(__('backend.platform_calendar.month_names.september')),
    @json(__('backend.platform_calendar.month_names.october')),
    @json(__('backend.platform_calendar.month_names.november')),
    @json(__('backend.platform_calendar.month_names.december'))
];

const daysOfWeek = [
    @json(__('backend.platform_calendar.day_names.sun')),
    @json(__('backend.platform_calendar.day_names.mon')),
    @json(__('backend.platform_calendar.day_names.tue')),
    @json(__('backend.platform_calendar.day_names.wed')),
    @json(__('backend.platform_calendar.day_names.thu')),
    @json(__('backend.platform_calendar.day_names.fri')),
    @json(__('backend.platform_calendar.day_names.sat'))
];

    let currentMonth=0;
    let currentYear=2026;
    let currentView='month';
    let events={};
    let selectedDate='';

    const calendarContent=document.getElementById('calendarContent');
    const viewModal=new bootstrap.Modal(document.getElementById('modal-view-event'));
    const addModal=new bootstrap.Modal(document.getElementById('modal-view-event-add'));

    async function fetchEvents(){
        const res=await fetch('{{ url("manager/calendar/events") }}');
        events=await res.json();
        renderCalendar();
    }

    function renderCalendar(){
        calendarContent.innerHTML='';
        const label=document.getElementById('currentViewLabel');

        if(currentView==='month'){
            label.innerText=`${months[currentMonth]} ${currentYear}`;
            const weekdaysRow=document.createElement('div');
            weekdaysRow.className='weekdays bg-light border-bottom';
            daysOfWeek.forEach(d=>weekdaysRow.innerHTML+=`<div>${d}</div>`);
            calendarContent.appendChild(weekdaysRow);

            const daysContainer=document.createElement('div');
            daysContainer.className='days flex-wrap d-flex';
            const firstDay=new Date(currentYear,currentMonth,1).getDay();
            const lastDate=new Date(currentYear,currentMonth+1,0).getDate();

            for(let i=0;i<firstDay;i++) daysContainer.innerHTML+='<div class="day-cell"></div>';
            for(let i=1;i<=lastDate;i++){
                const key=`${currentYear}-${String(currentMonth+1).padStart(2,'0')}-${String(i).padStart(2,'0')}`;
                const dayDiv=document.createElement('div');
                dayDiv.className='day-cell';
                dayDiv.dataset.date=key;
                let html=`<b>${i}</b>`;
                if(events[key]) events[key].forEach(e=>{
                    html+=`<div class="event ${e.color}" data-bs-toggle="tooltip" title="${e.description}">${e.title}</div>`;
                });
                dayDiv.innerHTML=html;
                dayDiv.addEventListener('click',()=>openDay(key));
                daysContainer.appendChild(dayDiv);
            }
            calendarContent.appendChild(daysContainer);
        } else if(currentView==='week'){
            label.innerText=`${__('backend.platform_calendar.week_of')} ${months[currentMonth]} ${currentYear}`;
            const weekRow=document.createElement('div');
            weekRow.className='week-row';
            for(let i=1;i<=7;i++){
                const key=`${currentYear}-${String(currentMonth+1).padStart(2,'0')}-${String(i).padStart(2,'0')}`;
                const dayDiv=document.createElement('div');
                dayDiv.className='day-cell';
                dayDiv.innerHTML=`<b>${daysOfWeek[i-1]}</b><br><small>${i}</small>`;
                if(events[key]) events[key].forEach(e=>{
                    dayDiv.innerHTML+=`<div class="event ${e.color}" data-bs-toggle="tooltip" title="${e.description}">${e.title}</div>`;
                });
                dayDiv.addEventListener('click',()=>openDay(key));
                weekRow.appendChild(dayDiv);
            }
            calendarContent.appendChild(weekRow);
        } else {
            label.innerText=`${__('backend.platform_calendar.day_of')} ${months[currentMonth]} ${currentYear}`;
            const dayDiv=document.createElement('div');
            dayDiv.className='day-view';
            const key=`${currentYear}-${String(currentMonth+1).padStart(2,'0')}-01`;
            const dayCell=document.createElement('div');
            dayCell.className='day-cell';
            dayCell.innerHTML=`<b>1</b>`;
            if(events[key]) events[key].forEach(e=>{
                dayCell.innerHTML+=`<div class="event ${e.color}" data-bs-toggle="tooltip" title="${e.description}">${e.title}</div>`;
            });
            dayCell.addEventListener('click',()=>openDay(key));
            dayDiv.appendChild(dayCell);
            calendarContent.appendChild(dayDiv);
        }

        const tooltipTriggerList=[].slice.call(calendarContent.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(el=>new bootstrap.Tooltip(el));
    }

    function openDay(date){
        selectedDate=date;
        document.getElementById('eventDateTitle').innerText=date;
        const list=document.getElementById('modalEventsList');
        list.innerHTML='';
        const controls=document.getElementById('modalEventsControls');
        controls.innerHTML='';

        if(events[date]){
            events[date].forEach(e=>{
                const div=document.createElement('div');
                div.className='event '+e.color+' d-flex align-items-center mb-1';
                const checkbox=document.createElement('input');
                checkbox.type='checkbox';
                checkbox.value=e.id;
                checkbox.className='me-2';
                div.appendChild(checkbox);
                div.appendChild(document.createTextNode(e.title));
                list.appendChild(div);
            });

            const selectAllBtn=document.createElement('button');
            selectAllBtn.textContent=@json(__('backend.platform_calendar.select_all'));
            selectAllBtn.className='btn btn-sm btn-secondary me-2';
            selectAllBtn.onclick=()=>{ list.querySelectorAll('input[type="checkbox"]').forEach(cb=>cb.checked=true); };

            const deleteSelectedBtn=document.createElement('button');
            deleteSelectedBtn.textContent=@json(__('backend.platform_calendar.delete_selected'));
            deleteSelectedBtn.className='btn btn-sm btn-danger me-2';
            deleteSelectedBtn.onclick=()=>deleteEvents(Array.from(list.querySelectorAll('input[type="checkbox"]:checked')).map(cb=>cb.value));

            controls.appendChild(selectAllBtn);
            controls.appendChild(deleteSelectedBtn);

        } else { list.innerHTML=`<i>${@json(__('backend.platform_calendar.no_events'))}</i>`; }

        viewModal.show();
    }

    async function deleteEvents(ids){
        if(ids.length===0) return alert(@json(__('backend.platform_calendar.no_events_selected')));
        const res=await fetch('{{ url("manager/calendar/delete-events") }}',{
            method:'POST',
            headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content},
            body:JSON.stringify({ids})
        });
        const data=await res.json();
        if(data.success){ fetchEvents(); openDay(selectedDate); } else alert(@json(__('backend.platform_calendar.failed_delete_events')));
    }

    document.getElementById('openAddEvent').addEventListener('click',()=>{ viewModal.hide(); addModal.show(); });
    const addEventForm=document.getElementById('add-event-form');
    if(!addEventForm.dataset.listenerAdded){
        addEventForm.addEventListener('submit', async e=>{
            e.preventDefault();
            const res=await fetch('{{ url("manager/calendar/add-event") }}',{
                method:'POST',
                headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content},
                body:JSON.stringify({
                    title:e.target.title.value,
                    description:e.target.description.value,
                    color:e.target.color.value,
                    icon:e.target.icon.value,
                    event_date:selectedDate
                })
            });
            const data=await res.json();
            if(data.success){ addEventForm.reset(); addModal.hide(); fetchEvents(); } else alert(@json(__('backend.platform_calendar.failed_save_event')));
        });
        addEventForm.dataset.listenerAdded="true";
    }

    document.getElementById('prevBtn').addEventListener('click',()=>{ currentMonth--; if(currentMonth<0){currentMonth=11; currentYear--;} renderCalendar(); });
    document.getElementById('nextBtn').addEventListener('click',()=>{ currentMonth++; if(currentMonth>11){currentMonth=0; currentYear++;} renderCalendar(); });

    document.getElementById('monthViewBtn').addEventListener('click',()=>{currentView='month';renderCalendar();});
    document.getElementById('weekViewBtn').addEventListener('click',()=>{currentView='week';renderCalendar();});
    document.getElementById('dayViewBtn').addEventListener('click',()=>{currentView='day';renderCalendar();});

    fetchEvents();
});
@endsection