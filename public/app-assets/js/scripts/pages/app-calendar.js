/**
 * App Calendar
 */

/**
 * ! If both start and end dates are same Full calendar will nullify the end date value.
 * ! Full calendar will end the event on a day before at 12:00:00AM thus, event won't extend to the end date.
 * ! We are getting events from a separate file named app-calendar-events.js. You can add or remove events from there.
 **/

'use-strict';

// RTL Support
var direction = 'ltr',
    assetPath = '../../../app-assets/';
if ($('html').data('textdirection') == 'rtl') {
    direction = 'rtl';
}

if ($('body').attr('data-framework') === 'laravel') {
    assetPath = $('body').attr('data-asset-path');
}

$(document).on('click', '.fc-sidebarToggle-button', function (e) {
    $('.app-calendar-sidebar, .body-content-overlay').addClass('show');
});

$(document).on('click', '.body-content-overlay', function (e) {
    $('.app-calendar-sidebar, .body-content-overlay').removeClass('show');
});

document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('calendar'),
        eventToUpdate,
        sidebar = $('.event-sidebar'),
        calendarsColor = {},
        eventForm = $('.event-form'),
        addEventBtn = $('.add-event-btn'),
        cancelBtn = $('.btn-cancel'),
        updateEventBtn = $('.update-event-btn'),
        toggleSidebarBtn = $('.btn-toggle-sidebar'),
        eventTitle = $('#title'),
        eventDescription = $('#description'),
        eventLabel = $('#select-label'),
        startDate = $('#start-date'),
        endDate = $('#end-date'),
        eventUrl = $('#event-url'),
        eventGuests = $('#event-guests'),
        eventLocation = $('#event-location'),
        allDaySwitch = $('.allDay-switch'),
        selectAll = $('.select-all'),
        calEventFilter = $('.calendar-events-filter'),
        filterInput = $('.input-filter'),
        btnDeleteEvent = $('.btn-delete-event'),
        calendarEditor = $('#event-description-editor');

    // --------------------------------------------
    // On add new item, clear sidebar-right field fields
    // --------------------------------------------
    $('.add-event button').on('click', function (e) {
        $('.event-sidebar').addClass('show');
        $('.sidebar-left').removeClass('show');
        $('.app-calendar .body-content-overlay').addClass('show');
    });

    // Label  select
    if (eventLabel.length) {
        function renderBullets(option) {
            if (!option.id) {
                return option.text;
            }
            var $bullet =
                "<span class='bullet bullet-" +
                $(option.element).data('label') +
                " bullet-sm mr-50'> " +
                '</span>' +
                option.text;

            return $bullet;
        }
        eventLabel.wrap('<div class="position-relative"></div>').select2({
            placeholder: 'Select value',
            dropdownParent: eventLabel.parent(),
            templateResult: renderBullets,
            templateSelection: renderBullets,
            minimumResultsForSearch: -1,
            escapeMarkup: function (es) {
                return es;
            }
        });
    }

    // Guests select
    if (eventGuests.length) {
        function renderGuestAvatar(option) {
            if (!option.id) {
                return option.text;
            }

            var $avatar =
                "<div class='d-flex flex-wrap align-items-center'>" +
                "<div class='avatar avatar-sm my-0 mr-50'>" +
                "<span class='avatar-content'>" +
                "<img src='" +
                assetPath +
                'images/avatars/' +
                $(option.element).data('avatar') +
                "' alt='avatar' />" +
                '</span>' +
                '</div>' +
                option.text +
                '</div>';

            return $avatar;
        }
        eventGuests.wrap('<div class="position-relative"></div>').select2({
            placeholder: 'Select value',
            dropdownParent: eventGuests.parent(),
            closeOnSelect: false,
            templateResult: renderGuestAvatar,
            templateSelection: renderGuestAvatar,
            escapeMarkup: function (es) {
                return es;
            }
        });
    }

    // Start date picker
    if (startDate.length) {
        var start = startDate.flatpickr({
            enableTime: true,
            altFormat: 'Y-m-dTH:i:S',
            onReady: function (selectedDates, dateStr, instance) {
                if (instance.isMobile) {
                    $(instance.mobileInput).attr('step', null);
                }
            }
        });
    }

    // End date picker
    if (endDate.length) {
        var end = endDate.flatpickr({
            enableTime: true,
            altFormat: 'Y-m-dTH:i:S',
            onReady: function (selectedDates, dateStr, instance) {
                if (instance.isMobile) {
                    $(instance.mobileInput).attr('step', null);
                }
            }
        });
    }

    // Event click function
    function eventClick(info) {
        eventToUpdate = info.event;
        if (eventToUpdate.url) {
            info.jsEvent.preventDefault();
            window.open(eventToUpdate.url, '_blank');
        }

        sidebar.modal('show');
        addEventBtn.addClass('d-none');
        cancelBtn.addClass('d-none');
        updateEventBtn.removeClass('d-none');
        btnDeleteEvent.removeClass('d-none');

        eventTitle.val(eventToUpdate.title);
        eventDescription.val(eventToUpdate.description);
        start.setDate(eventToUpdate.start, true, 'Y-m-d');
        eventToUpdate.allDay === true ? allDaySwitch.prop('checked', true) : allDaySwitch.prop('checked', false);
        eventToUpdate.end !== null
            ? end.setDate(eventToUpdate.end, true, 'Y-m-d')
            : end.setDate(eventToUpdate.start, true, 'Y-m-d');
        sidebar.find(eventLabel).val(eventToUpdate.extendedProps.calendar).trigger('change');
        eventToUpdate.extendedProps.location !== undefined ? eventLocation.val(eventToUpdate.extendedProps.location) : null;
        eventToUpdate.extendedProps.guests !== undefined
            ? eventGuests.val(eventToUpdate.extendedProps.guests).trigger('change')
            : null;
        eventToUpdate.extendedProps.guests !== undefined
            ? calendarEditor.val(eventToUpdate.extendedProps.description)
            : null;

        //  Delete Event
        btnDeleteEvent.on('click', function () {
            eventToUpdate.remove();
            // removeEvent(eventToUpdate.id);
            sidebar.modal('hide');
            $('.event-sidebar').removeClass('show');
            $('.app-calendar .body-content-overlay').removeClass('show');
        });
    }

    // Modify sidebar toggler
    function modifyToggler() {
        $('.fc-sidebarToggle-button')
            .empty()
            .append(feather.icons['menu'].toSvg({ class: 'ficon' }));
    }

    // Selected Checkboxes
    function selectedCalendars() {
        var selected = [];
        $('.calendar-events-filter input:checked').each(function () {
            selected.push($(this).attr('data-value'));
        });
        return selected;
    }

    // --------------------------------------------------------------------------------------------------
    // AXIOS: fetchEvents
    // * This will be called by fullCalendar to fetch events. Also this can be used to refetch events.
    // --------------------------------------------------------------------------------------------------
    function fetchEvents(info, successCallback) {
        // Fetch Events from API endpoint reference
        $.ajax({
            url: calendarDataUrl,
            type: 'POST',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function (response) {
                // Get unique calendar types from events data
                var uniqueTypes = [...new Set(response.map(event => event.type))];
                // Mapping each unique type to its color
                uniqueTypes.forEach(type => {
                    const event = response.find(event => event.type === type);
                    calendarsColor[type] = event.color;
                });
                var calendars = selectedCalendars();

                var filteredEvents = response.filter(function(eventData) {
                    return calendars.includes(eventData.type);
                });

                var transformedEvents = filteredEvents.map(function (eventData) {
                    return {
                        id: eventData.id,
                        title: eventData.title,
                        description: eventData.description,
                        start: eventData.starts_at,
                        end: eventData.starts_at,
                        type: eventData.type
                    };
                });
                successCallback(transformedEvents);
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }

    // Calendar plugins
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        events: fetchEvents,
        editable: false,
        dragScroll: false,
        dayMaxEvents: 2,
        eventResizableFromStart: true,
        customButtons: {
            sidebarToggle: {
                text: 'Sidebar'
            }
        },
        headerToolbar: {
            start: 'sidebarToggle, prev,next, title',
            end: 'timeGridDay,dayGridMonth'
        },
        direction: direction,
        initialDate: new Date(),
        navLinks: true, // can click day/week names to navigate views
        eventClassNames: function ({ event: calendarEvent }) {
            const colorName = calendarsColor[calendarEvent._def.extendedProps.type];
            if (colorName) {
                return ['bg-light-' + colorName];
            }
            return []; // Return empty array if color not found
        },
        eventContent: function(arg) {
            var html = '<div class="fc-content">' +
            '<span class="fc-time">' + moment(arg.event.start).format('h:mm a') + '</span><br>' +
            '<span class="fc-title">' + arg.event.title + '</span>';

            // Check if the current view is not the month view
            if (arg.view.type !== 'dayGridMonth' && arg.event.extendedProps.description !=null) {
                html += '<div class="fc-description">' + arg.event.extendedProps.description + '</div>';
            }

            html += '</div>';
            return { html: html };
        },

        dateClick: function (info) {
            var date = moment(info.date).format('YYYY-MM-DD');
            resetValues();
            sidebar.modal('show');
            addEventBtn.removeClass('d-none');
            updateEventBtn.addClass('d-none');
            btnDeleteEvent.addClass('d-none');
            startDate.val(date);
            endDate.val(date);
        },
        eventClick: function (info) {
            eventClick(info);
        },
        datesSet: function () {
            modifyToggler();
        },
        viewDidMount: function () {
            modifyToggler();
        }
    });

    // Render calendar
    calendar.render();
    // Modify sidebar toggler
    modifyToggler();
    // updateEventClass();

    // Validate add new and update form
    if (eventForm.length) {
        eventForm.validate({
            submitHandler: function (form, event) {
                event.preventDefault();
                if (eventForm.valid()) {
                    sidebar.modal('hide');
                }
            },
            title: {
                required: true
            },
            'start-date': {
                required: true
            },
            'end-date': {
                required: true
            }
        });
    }

    // Sidebar Toggle Btn
    if (toggleSidebarBtn.length) {
        toggleSidebarBtn.on('click', function () {
            cancelBtn.removeClass('d-none');
        });
    }

    // ------------------------------------------------
    // addEvent
    // ------------------------------------------------
    function addEvent(eventData) {
        calendar.addEvent(eventData);
        calendar.refetchEvents();
    }

    // ------------------------------------------------
    // updateEvent
    // ------------------------------------------------
    function updateEvent(eventData) {
        var propsToUpdate = ['id', 'title', 'url'];
        var extendedPropsToUpdate = ['calendar', 'guests', 'location', 'description'];

        updateEventInCalendar(eventData, propsToUpdate, extendedPropsToUpdate);
    }

    // ------------------------------------------------
    // removeEvent
    // ------------------------------------------------
    function removeEvent(eventId) {
        removeEventInCalendar(eventId);
    }

    // ------------------------------------------------
    // (UI) updateEventInCalendar
    // ------------------------------------------------
    const updateEventInCalendar = (updatedEventData, propsToUpdate, extendedPropsToUpdate) => {
        const existingEvent = calendar.getEventById(updatedEventData.id);

        // --- Set event properties except date related ----- //
        // ? Docs: https://fullcalendar.io/docs/Event-setProp
        // dateRelatedProps => ['start', 'end', 'allDay']
        // eslint-disable-next-line no-plusplus
        for (var index = 0; index < propsToUpdate.length; index++) {
            var propName = propsToUpdate[index];
            existingEvent.setProp(propName, updatedEventData[propName]);
        }

        // --- Set date related props ----- //
        // ? Docs: https://fullcalendar.io/docs/Event-setDates
        existingEvent.setDates(updatedEventData.start, updatedEventData.end, { allDay: updatedEventData.allDay });

        // --- Set event's extendedProps ----- //
        // ? Docs: https://fullcalendar.io/docs/Event-setExtendedProp
        // eslint-disable-next-line no-plusplus
        for (var index = 0; index < extendedPropsToUpdate.length; index++) {
            var propName = extendedPropsToUpdate[index];
            existingEvent.setExtendedProp(propName, updatedEventData.extendedProps[propName]);
        }
    };

    // ------------------------------------------------
    // (UI) removeEventInCalendar
    // ------------------------------------------------
    function removeEventInCalendar(eventId) {
        calendar.getEventById(eventId).remove();
    }

    // Add new event
    $(addEventBtn).on('click', function () {
        if (eventForm.valid()) {
            var newEvent = {
                id: calendar.getEvents().length + 1,
                title: eventTitle.val(),
                description: eventDescription.val(),
                start: startDate.val(),
                end: endDate.val(),
                startStr: startDate.val(),
                endStr: endDate.val(),
                display: 'block',
                extendedProps: {
                    location: eventLocation.val(),
                    guests: eventGuests.val(),
                    calendar: eventLabel.val(),
                    description: calendarEditor.val()
                }
            };
            if (eventUrl.val().length) {
                newEvent.url = eventUrl.val();
            }
            if (allDaySwitch.prop('checked')) {
                newEvent.allDay = true;
            }
            addEvent(newEvent);
        }
    });

    // Update new event
    updateEventBtn.on('click', function () {
        if (eventForm.valid()) {
            var eventData = {
                id: eventToUpdate.id,
                title: sidebar.find(eventTitle).val(),
                description: sidebar.find(eventDescription).val(),
                start: sidebar.find(startDate).val(),
                end: sidebar.find(endDate).val(),
                url: eventUrl.val(),
                extendedProps: {
                    location: eventLocation.val(),
                    guests: eventGuests.val(),
                    calendar: eventLabel.val(),
                    description: calendarEditor.val()
                },
                display: 'block',
                allDay: allDaySwitch.prop('checked') ? true : false
            };

            updateEvent(eventData);
            sidebar.modal('hide');
        }
    });

    // Reset sidebar input values
    function resetValues() {
        endDate.val('');
        eventUrl.val('');
        startDate.val('');
        eventTitle.val('');
        eventDescription.val('');
        eventLocation.val('');
        allDaySwitch.prop('checked', false);
        eventGuests.val('').trigger('change');
        calendarEditor.val('');
    }

    // When modal hides reset input values
    sidebar.on('hidden.bs.modal', function () {
        resetValues();
    });

    // Hide left sidebar if the right sidebar is open
    $('.btn-toggle-sidebar').on('click', function () {
        btnDeleteEvent.addClass('d-none');
        updateEventBtn.addClass('d-none');
        addEventBtn.removeClass('d-none');
        $('.app-calendar-sidebar, .body-content-overlay').removeClass('show');
    });

    // Select all & filter functionality
    if (selectAll.length) {
        selectAll.on('change', function () {
            var $this = $(this);

            if ($this.prop('checked')) {
                calEventFilter.find('input').prop('checked', true);
            } else {
                calEventFilter.find('input').prop('checked', false);
            }
            calendar.refetchEvents();
        });
    }

    if (filterInput.length) {
        filterInput.on('change', function () {
            $('.input-filter:checked').length < calEventFilter.find('input').length
                ? selectAll.prop('checked', false)
                : selectAll.prop('checked', true);
            calendar.refetchEvents();
        });
    }
});
