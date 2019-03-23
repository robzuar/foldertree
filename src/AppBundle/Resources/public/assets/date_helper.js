/*
    Symfony use date id convencion like:
    "form_start_(day, year or month)"
    when we set in form:
    'widget' => 'text',
    'format' => 'dd-MM-yyyy',
    to get day, month and year separate fields.
    Script cuts id from '.form-control' and by combining it with name
    passed by user make fill date fields easier.
    - It takes care to avoid pass more than x characters per field,
    - It clears field onclick
    - It checks if only numbers are passed (red font when not)
    - It generates calendar to eisier pic dates
*/

class DateFields
{
    constructor(fieldName) {
        this.fieldName = fieldName;
        this.day = `#${fieldName}_day`;
        this.month = `#${fieldName}_month`;
        this.year = `#${fieldName}_year`;      
        this.dateField = `#${fieldName}`;
        this.fieldWidthMultipler = 20;
        this.dayChars = 2; 
        this.monthChars = 2; 
        this.yearChars = 4; 
        this.setUpAssistants();
    }

    //invoke all assistants methods
    setUpAssistants() {
        this.dayAssistant();
        this.monthAssistant();
        this.yearAssistant();
    }

    dayAssistant() {
        let dayField = document.querySelector(this.day);
        dayField.style.position = 'relative';
        dayField.style.width = `${this.fieldWidthMultipler * this.dayChars}px`;
        this.resetFieldData(dayField);
        this.checkIsNumber(dayField);
        this.preventOverfill(dayField, this.dayChars);
        this.checkDayChange(dayField, this.fieldName);
    }

    monthAssistant() {
        let monthField = document.querySelector(this.month);
        monthField.style.position = 'relative';
        monthField.style.width = `${this.fieldWidthMultipler * this.monthChars}px`;
        this.resetFieldData(monthField);
        this.checkIsNumber(monthField);
        this.preventOverfill(monthField, this.monthChars);
        this.checkMonthChange(monthField, this.fieldName);     
    }

    yearAssistant() {
        let yearField = document.querySelector(this.year);
        yearField.style.position = 'relative';
        yearField.style.width = `${this.fieldWidthMultipler * this.yearChars}px`;
        this.resetFieldData(yearField);
        this.checkIsNumber(yearField);
        this.preventOverfill(yearField, this.yearChars);
        this.checkYearChange(yearField, this.fieldName);         
    }

    //reset field data onclick
    resetFieldData(fieldNameData) {
        fieldNameData.onclick = function(){        
            fieldNameData.value = '';        
        };
    }
    //avoid to pass more characters than field Length
    preventOverfill(fieldNameData, fieldLength) {   
        fieldNameData.addEventListener("keyup", function(e){        
            if (this.value.length >= fieldLength) { 
                this.value = this.value.substr(0, fieldLength);
            }              
        });
    }
    
    //check if only number is passed
    checkIsNumber(fieldNameData) {
        fieldNameData.addEventListener("keyup", function(e){        
            if (isNaN(this.value)) { 
                fieldNameData.style.color = "red";
            } else {
                fieldNameData.style.color = "black";
            }
        });
    }

    //check if Day is changed to change Day value in calendar
    checkDayChange(fieldNameData, fieldName) { 
        fieldNameData.addEventListener("keyup", function(e){   
            let calendarObjectData = calendarObjectsData[fieldName];    
            calendarObjectData.changeDay(parseInt(this.value));
        });
    }

    //check if Month is changed to change Month value in calendar
    checkMonthChange(fieldNameData, fieldName) { 
        fieldNameData.addEventListener("keyup", function(e){   
            let calendarObjectData = calendarObjectsData[fieldName];    
            calendarObjectData.changeMonth(parseInt(this.value));
        });
    }

    //check if year is changed to change year value in calendar
    checkYearChange(fieldNameData, fieldName) { 
        fieldNameData.addEventListener("keyup", function(e){   
            let calendarObjectData = calendarObjectsData[fieldName];    
            calendarObjectData.changeYear(parseInt(this.value));
        });
    } 
   
}

class CalendarIco
{
    constructor(dateField) {
        this.calendarIco = `${dateField}_calendarIco`;
        this.dateField = dateField;
        this.insertCalendarIco();
    }

    //insert calendar ico after year field
    insertCalendarIco() {

        let calendarSpan = document.createElement('span');
        let yearField = document.querySelector(`#${this.dateField}_year`);
        calendarSpan.className = 'glyphicon glyphicon-calendar';
        calendarSpan.id = this.calendarIco;
        let fieldName = this.dateField;

        calendarSpan.onclick = function() {
            let objectData = calendarObjectsData[fieldName];            
            objectData.setState();
        };
        
        yearField.parentNode.insertBefore(calendarSpan, yearField.nextSibling);
    }
}

class DaySelector
{
    
    constructor(dateField) {
        this.calendarName = `${dateField}_calendar`;        
        this.dateField = dateField;
    }
    
    //check if leap year
    leapYearCheck(year) {
        if (year % 4 == 0 && year % 100 != 0 || year % 400 == 0)
        {        
            return [0,31,29,31,30,31,30,31,31,30,31,30,31];        
        }
        return [0,31,28,31,30,31,30,31,31,30,31,30,31];
    }

    createDayButtons(month, year) {
        //get starting day for calendar to get week day that begins current month
        let startDay = new Date(`${year}-${month}-01`);                
        let startWeekDay = startDay.getDay();
        //case when start week day is sunday we must add week to our value to proper pass sunday into calendar
        if (startWeekDay == 0) {
            startWeekDay += 7;
        }
        let buttonDay;  
        let numberOfDaysInMonth = this.leapYearCheck(year); 
        numberOfDaysInMonth = parseInt(numberOfDaysInMonth[month]) + startWeekDay; 
        
        //create week day names header
        for (let i = 1; i <= 7; i++) {
            this.createDayButton(i, 4);
        }
        //create days buttons
        let i = 1;
        while (i <= numberOfDaysInMonth) {
            buttonDay = i - startWeekDay + 1;
            if (i < numberOfDaysInMonth) {
                //create button type (color) depend on week day
                if (i >= startWeekDay) {                    
                    if (i % 7 == 6) {
                        this.createDayButton(buttonDay, 2);
                    } else if (i % 7 == 0) {
                        this.createDayButton(buttonDay, 3);
                    } else {
                        this.createDayButton(buttonDay, 1);
                    }
                } else {
                    this.createDayButton(buttonDay, 0);
                }
            }
            i++;
        }
    }
    
    //onclick function change date and hide calendar
    createOnclickDay(day, dateFieldData) {     
        let calendarObjectData = calendarObjectsData[dateFieldData];
        calendarObjectData.setDay(day); 
    }

    //create day buttons for month
    createDayButton(day, type) {  

        let dayButton = document.createElement('button');
        //set names for week days
        let weekDayNames;
        //set month names in Polish or English depend on browserlanguage
        let userLang = navigator.language || navigator.userLanguage;
        if (userLang == 'en-US') {
            weekDayNames = ['','mon','tue','wed','th','fr','sat','sun'];
        } else {
            weekDayNames = ['','pon.','wt.','śr.','czw.','pt.','sob.','nd.'];
        }
        
        dayButton.innerHTML = day;
        dayButton.type = 'button';
                
        switch(type) {
            case 0:
                dayButton.className = 'calendar-day-empty';
                document.querySelector(`#${this.dateField}_calendar`).appendChild(dayButton);
                break;
            case 1:
                dayButton.onclick = () => {
                    this.createOnclickDay(day, this.dateField);
                };
                dayButton.className = 'calendar-day-week';
                document.querySelector(`#${this.dateField}_calendar`).appendChild(dayButton);
                break;
            case 2:
                dayButton.onclick = () => {
                    this.createOnclickDay(day, this.dateField);
                };
                dayButton.className = 'calendar-day-saturday';
                document.querySelector(`#${this.dateField}_calendar`).appendChild(dayButton);
                break;
            case 3:
                dayButton.onclick = () => {
                    this.createOnclickDay(day, this.dateField);
                };
                dayButton.className = 'calendar-day-sunday';
                document.querySelector(`#${this.dateField}_calendar`).appendChild(dayButton);
                break;
            case 4:
                dayButton.className = 'calendar-day-week-names';
                dayButton.innerHTML = weekDayNames[day];
                document.querySelector(`#${this.dateField}_calendar`).appendChild(dayButton);
                break;
        }        
    }
}

class MonthSelector
{
    constructor(dateField) {
        this.calendarName = `${dateField}_calendar`;        
        this.dateField = dateField;
    }
    
    setCalendarObjectData() {
        this.calendarObjectData = calendarObjectsData[this.dateField];
    }
    //use setYear method on Calendar object
    yearChange(operator) {
        this.setCalendarObjectData();
        this.calendarObjectData.setYear(operator);
    }
    //use setYear method on Calendar object
    monthChange(month) {
        this.setCalendarObjectData();
        this.calendarObjectData.setMonth(month);
    }
    //method to generate month selector in calendar (increase or decrease value of month onclick)
    createMonthSelection(month, dateField) {
        let increaseButton = document.createElement('button');        
        let monthData = document.createElement('button');
        let decreaseButton = document.createElement('button');

        //set names for months
        let monthNames;
        //set month names in Polish or English depend on browserlanguage
        let userLang = navigator.language || navigator.userLanguage;
        if (userLang == 'en-US') {
            monthNames = ['','January','Febuary','March','April','May','June','July','August','September','October','November','December'];
        } else {
            monthNames = ['','Styczeń','Luty','Marzec','Kwiecień','Maj','Czerwiec','Lipiec','Sierpień','Wrzesień','Październik','Listopad','Grudzień'];
        } 

        //create decrease button (to change month value)
        decreaseButton.className = 'calendar-month-change';    
        decreaseButton.innerHTML = '<';
        decreaseButton.type = 'button';
        document.querySelector(`#${this.dateField}_calendar`).appendChild(decreaseButton);
        decreaseButton.onclick = () => {
            month--;
            //if month >= 1 than decrease value of month
            if (month >= 1) {                
                document.querySelector(`#${dateField}_month`).value = parseInt(month);
                monthData.innerHTML = monthNames[month];
                this.monthChange(month);
            } else { 
                //if month < 1 than change year value and set month to december
                month = 12;               
                document.querySelector(`#${dateField}_month`).value = month;
                monthData.innerHTML = monthNames[month];
                this.yearChange('-');
                this.monthChange(month);
            }
        };

        //create month field (show selected month name)
        monthData.innerHTML = monthNames[month];
        monthData.className = 'calendar-month';
        monthData.type = 'button';
        document.querySelector(`#${this.dateField}_calendar`).appendChild(monthData);
        
        //create increase button (to change month value)
        increaseButton.className = 'calendar-month-change';    
        increaseButton.innerHTML = '>';
        increaseButton.type = 'button';
        document.querySelector(`#${this.dateField}_calendar`).appendChild(increaseButton);
        increaseButton.onclick = () => {
            month++;
            //if month <= 12 than increase value of month
            if (month <= 12) {
                document.querySelector(`#${dateField}_month`).value = parseInt(month);
                monthData.innerHTML = monthNames[month];
                this.monthChange(month);
            } else {
                //if month > 12 than change year value and set month to january
                month = 1;
                document.querySelector(`#${dateField}_month`).value = month;
                monthData.innerHTML = monthNames[month];
                this.yearChange('+');
                this.monthChange(month);
            }
        };  
    }
}

class Calendar
{
    constructor(dateField, monthSelector, daySelector) {
        this.calendarName = `${dateField}_calendar`;
        //set date data if fields are filled
        this.today = new Date();
        //if fields are empty use today as start date (else use data loaded from db)
        if (document.querySelector(`#${dateField}_day`).value) {
            this.day = parseInt(document.querySelector(`#${dateField}_day`).value);
        } else {
            this.day = this.today.getDate();
        }
        if (document.querySelector(`#${dateField}_month`).value && !isNaN(parseInt(document.querySelector(`#${dateField}_month`).value))) {
            this.month = parseInt(document.querySelector(`#${dateField}_month`).value);            
        } else {
            this.month = parseInt(this.today.getMonth()) + 1; 
        }
        if (document.querySelector(`#${dateField}_year`).value) {
            this.year = parseInt(document.querySelector(`#${dateField}_year`).value);
        } else {
            this.year = this.today.getFullYear();
        }
        
        this.dateField = dateField;
        this.state = 0;
        //create calendar field
        this.calendar = document.createElement('div');
        this.createCalendar(this.calendar, this.calendarName, this.dateField);
        
        //create calendar body (month and day select)
        this.monthSelector = monthSelector;
        this.daySelector = daySelector;
        this.createMonthSelection(this.monthSelector);
        this.createDaySelection(this.daySelector);
    }

    //create calendar field
    createCalendar(calendar, calendarName, dateField) {   
        calendar.className = 'calendar';
        calendar.id = calendarName;
        calendar.style.display = 'none';        
        document.querySelector(`#${dateField}`).appendChild(calendar);
    }

    //show / hide calendar
    setState() {
        if (this.state) {
            this.state = 0;
            this.calendar.style.display = 'none';
        } else {
            this.state = 1;
            this.calendar.style.display = 'inline-block';
        }
    } 
    
    resetCalendar() {        
        //clear calendar field
        document.querySelector(`#${this.dateField}_calendar`).innerHTML = '';
        this.createMonthSelection(this.monthSelector);
        this.createDaySelection(this.daySelector);
    }

    //change month state
    setDay(day) {
        this.day = day;
        //fill date fields when day is changed
        document.querySelector(`#${this.dateField}_day`).value = this.day;
        document.querySelector(`#${this.dateField}_month`).value = this.month;
        document.querySelector(`#${this.dateField}_year`).value = this.year;
        this.setState();
    }

    //change day value
    changeDay(day) {        
        this.day = day;
    }

    //change month state
    setMonth(month) {
        if(!isNaN(parseInt(month))) {
            this.month = month;
        }            
        //fill date fields when month is changed
        document.querySelector(`#${this.dateField}_day`).value = this.day;
        document.querySelector(`#${this.dateField}_month`).value = this.month;
        document.querySelector(`#${this.dateField}_year`).value = this.year;
        //clear calendar field
        this.resetCalendar();
    }

    //change month value
    changeMonth(month) {        
        this.month = month;
        this.resetCalendar();
    }

    //change year (increase / decrease by 1)
    setYear(operator) {
        //change year value depend of year increase or decrease
        if (operator == '+') {
            this.year += 1;
            document.querySelector(`#${this.dateField}_year`).value = this.year;
        } else {
            this.year -= 1;
            document.querySelector(`#${this.dateField}_year`).value = this.year;
        }
    } 

    //change year value
    changeYear(year) {        
        this.year = year;
        //clear calendar field
        this.resetCalendar();
    }

    //create month selection field
    createMonthSelection(monthSelector) {
        monthSelector.createMonthSelection(this.month, this.dateField);
    }
    //create day selection field
    createDaySelection(daySelector) {
        daySelector.createDayButtons(this.month, this.year);
    }
      
}

//class that after get instantiate create date fill helpers and calendars for all dates on page
class DateObjectCreator
{    
    //cut name from id
    getFormName(formId) {
        let res = formId.split("_");
        res.pop();//cut "day"        
        return res.join('_');
    }

    //get all dateType forms
    findDateForms() {
        let inputs = document.querySelectorAll(`input[name*='[day]']`); 
        let idData;
        let formObjects = [];
        let calendarObjects = [];
        let calendarIcoObjects = [];

        if (inputs.length > 0) { 
            for (let i=0; i < inputs.length; i++) { 
                //get form names and create objects for all
                idData = this.getFormName(inputs[i].getAttribute('id')); 
                //create arrays of calendar objects
                calendarObjects[idData] = new Calendar(idData, new MonthSelector(idData), new DaySelector(idData));
                calendarIcoObjects[idData] = new CalendarIco(idData);
                //create date fill helpers
                formObjects[idData] = new DateFields(idData);
            }         
        } 
        return calendarObjects;  
    }

}

/*
    set up assistants for all date fields
    DateObjectCreator gets all date forms and create object for each one
*/
const createObjectDates = new DateObjectCreator();
const calendarObjectsData = createObjectDates.findDateForms();
//enjoy