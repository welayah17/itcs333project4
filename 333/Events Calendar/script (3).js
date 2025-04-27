function showTime() {
	document.getElementById('currentTime').innerHTML = new Date().toUTCString();
}
showTime();
setInterval(function () {
	showTime();
}, 1000);
 
        const calendar = document.getElementById('calendar');
        let selectedDay = null;
        let events = JSON.parse(localStorage.getItem('events')) || {};

        function renderCalendar() {
           function renderCalendar() {
    for (let i = 1; i <= 30; i++) {
        let day = document.createElement('div');
        day.className = 'day';
        day.textContent = i;
        day.onclick = () => selectDay(day);
        
        // التحقق مما إذا كان اليوم يحتوي على حدث
        if (events[i]) {
            day.classList.add('event');
            let eventLabel = document.createElement('div');
            eventLabel.className = 'event-name';
            eventLabel.textContent = events[i]; // عرض اسم الحدث تحت رقم اليوم
            day.appendChild(eventLabel);
        }
        calendar.appendChild(day);
    }
}

            }
        }
        
        function selectDay(day) {
            selectedDay = day;
            document.getElementById('eventForm').style.display = 'block';
        }

        function addEvent() {
            function addEvent() {
    if (selectedDay) {
        let eventText = document.getElementById('eventText').value;
        if (eventText.trim() !== '') {
            selectedDay.classList.add('event');
            let eventLabel = selectedDay.querySelector('.event-name');
            
            if (!eventLabel) {
                eventLabel = document.createElement('div');
                eventLabel.className = 'event-name';
                selectedDay.appendChild(eventLabel);
            }

            eventLabel.textContent = eventText; 
            events[selectedDay.textContent] = eventText; 
            localStorage.setItem('events', JSON.stringify(events));
            document.getElementById('eventText').value = '';
        }
    }
}

        }

        function removeEvent() {
            if (selectedDay) {
                selectedDay.classList.remove('event');
                selectedDay.removeAttribute('title');
                delete events[selectedDay.textContent];
                localStorage.setItem('events', JSON.stringify(events));
            }
        }

        renderCalendar();
  