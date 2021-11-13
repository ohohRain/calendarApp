var month_olympic = [31, 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
var month_normal = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
var month_name = ["January", "Febrary", "March", "April", "May", "June", "July", "Auguest", "September", "October", "November", "December"];

var holder = document.getElementById("days");
var prev = document.getElementById("prev");
var next = document.getElementById("next");
var c_month = document.getElementById("calendar-title");
var c_year = document.getElementById("calendar-year");

var my_date = new Date();
//console.log(my_date);
var my_year = my_date.getFullYear();
var my_month = my_date.getMonth();
var my_day = my_date.getDate();

var select_date;
var select_event_id;

function dayStart(month, year) {
	var tmpDate = new Date(year, month, 1);
	return (tmpDate.getDay());
}

function daysMonth(month, year) {
	var tmp = year % 4;
	if (tmp == 0) {
		return (month_olympic[month]);
	} else {
		return (month_normal[month]);
	}
}

function clickit(dateId) {


	let month = c_month.innerHTML;
	let year = c_year.innerHTML;
	console.log(dateId, month, year);

	document.getElementById(select_date).classList.remove('greenbox');
	document.getElementById(dateId).classList.add('greenbox');
	select_date = dateId;
	//alert(dateId+month+year);

	getEventList();
}

function refreshDate() {

	var str = "";
	var totalDay = daysMonth(my_month, my_year); //get days for one month
	var firstDay = dayStart(my_month, my_year);
	var myclass;
	var idNumber;
	var onClickFunction = " onclick='clickit(this.id)'"
	for (var i = 1; i < firstDay; i++) {
		str += "<li></li>"; //set the empty days
	}
	for (var i = 1; i <= totalDay; i++) {
		if ((i < my_day && my_year == my_date.getFullYear() && my_month == my_date.getMonth()) || my_year < my_date.getFullYear() || (my_year == my_date.getFullYear() && my_month < my_date.getMonth())) {
			myclass = " class='lightgrey'"; //days before today show as grey color
		} else if (i == my_day && my_year == my_date.getFullYear() && my_month == my_date.getMonth()) {
			myclass = " class='green greenbox'"; //today will be green background
			select_date = i;
		} else {
			myclass = " class='darkgrey'"; //days after today will be black color
		}
		idNumber = " id=" + i + "";
		str += "<li" + myclass + "" + idNumber + "" + onClickFunction + "><span style='cursor: pointer'>" + i + "</span></li>"; //create the date and set the onlickfunction

		//console.log(idNumber);
	}
	holder.innerHTML = str; //set html of date
	c_month.innerHTML = month_name[my_month]; //set month
	c_year.innerHTML = my_year; //set year
	//console.log(`${my_year}-${real_month}-${select_date}`);

	if (my_month != my_date.getMonth()) {
    	select_date = 1;
    	document.getElementById(select_date).classList.add('greenbox');
    }

    getEventList();
	
}

refreshDate(); // run this function


prev.onclick = function (e) {
	e.preventDefault();
	my_month--;
	if (my_month < 0) {
		my_year--;
		my_month = 11;
	}
	refreshDate();
}
next.onclick = function (e) {
	e.preventDefault();
	my_month++;
	if (my_month > 11) {
		my_year++;
		my_month = 0;
	}
	refreshDate();
}

function getEventList() {
	const real_month = my_month + 1;
	const date = `${my_year}-${real_month}-${select_date}`;

	const data = { "date": date};

	const JSON_SEND = JSON.stringify(data)

	var xhttp = new XMLHttpRequest();
    xhttp.addEventListener("load", function(event) {
		
        if (this.status == 200) {
            var JSON_DATA = JSON.parse(this.responseText);
			var events = JSON_DATA.events;
            var table = document.getElementById("js-table");
			console.log("refresh list")
            // clear table
            while (table.hasChildNodes()) {
                table.removeChild(table.lastChild);
            }
            // build table from content got from ajax
            for (var i = 0; i < events.length; i++) {
                // build new row
								var row = table.insertRow(i);
                var col1 = row.insertCell(0);
                var col2 = row.insertCell(1);
				var col3 = row.insertCell(2);
				var col4 = row.insertCell(3);
				var col5 = row.insertCell(4);
				var col6 = row.insertCell(5);
				var col7 = row.insertCell(6);

				var label = document.createElement("label");
                label.innerHTML = "Event: ";
                col1.appendChild(label);
                
                var label = document.createElement("label");
                label.className = "eventName";
								label.id = events[i].event_id;
                label.innerHTML = events[i].event;

                col2.appendChild(label);

				var label = document.createElement("label");
                label.className = "time";
								label.id = events[i].event_id;
                label.innerHTML = events[i].time;

                col3.appendChild(label);

							var label = document.createElement("label");
                label.className = "category";
								label.id = events[i].event_id;
                label.innerHTML = events[i].category;
				 
                col4.appendChild(label);
                // build delete button
                var button = document.createElement("button");
                button.className = "btn btn-info";
                button.innerHTML = "Edit";
                button.id = events[i].event_id;
                button.addEventListener("click", showEdit);
                col5.appendChild(button);

				var button = document.createElement("button");
                button.className = "btn btn-danger";
                button.innerHTML = "Delete";
                button.id = events[i].event_id;
                button.addEventListener("click", removeEvents);
                col6.appendChild(button);

				var button = document.createElement("button");
                button.className = "btn btn-success";
                button.innerHTML = "Share";
                button.id = events[i].event_id;
                button.addEventListener("click", shareEvent);
                col7.appendChild(button);

            }
            
        }
    }, false);

    xhttp.open("POST", "listEvent.php", true);
    xhttp.setRequestHeader("Content-Type", "application/json");
    xhttp.send(JSON_SEND);
}

function getCategoryEventList(category) {
	console.log(category);
	const real_month = my_month + 1;
	const date = `${my_year}-${real_month}-${select_date}`;

	const data = { "date": date, "category": category};
	
	const JSON_SEND = JSON.stringify(data)
	console.log(JSON_SEND);
	var xhttp = new XMLHttpRequest();
    xhttp.addEventListener("load", function(event) {
		
        if (this.status == 200) {
            var JSON_DATA = JSON.parse(this.responseText);
			var events = JSON_DATA.events;
			console.log(this.responseText);
            var table = document.getElementById("js-table");
			console.log("refresh list")
            // clear table
            while (table.hasChildNodes()) {
                table.removeChild(table.lastChild);
            }
            // build table from content got from ajax
            for (var i = 0; i < events.length; i++) {
                // build new row
                var row = table.insertRow(i);
                var col1 = row.insertCell(0);
                var col2 = row.insertCell(1);
				var col3 = row.insertCell(2);
				var col4 = row.insertCell(3);
				var col5 = row.insertCell(4);
				var col6 = row.insertCell(5);
				var col7 = row.insertCell(6);

				var label = document.createElement("label");
                label.innerHTML = "Event: ";
                col1.appendChild(label);
                
                var label = document.createElement("label");
                label.className = "eventName";
								label.id = events[i].event_id;
                label.innerHTML = events[i].event;
				 
                col2.appendChild(label);

				var label = document.createElement("label");
                label.className = "time";
								label.id = events[i].event_id;
                label.innerHTML = events[i].time;
				 
                col3.appendChild(label);

							var label = document.createElement("label");
                label.className = "category";
								label.id = events[i].event_id;
                label.innerHTML = events[i].category;
				 
                col4.appendChild(label);
                // build delete button
                var button = document.createElement("button");
                button.className = "btn btn-info";
                button.innerHTML = "Edit";
                button.id = events[i].event_id;
                button.addEventListener("click", showEdit);
                col5.appendChild(button);

				var button = document.createElement("button");
                button.className = "btn btn-danger";
                button.innerHTML = "Delete";
                button.id = events[i].event_id;
                button.addEventListener("click", removeEvents);
                col6.appendChild(button);

				var button = document.createElement("button");
                button.className = "btn btn-success";
                button.innerHTML = "Share";
                button.id = events[i].event_id;
                button.addEventListener("click", shareEvent);
                col7.appendChild(button);


            }
            
        }
    }, false);

    xhttp.open("POST", "categoryListEvent.php", true);
    xhttp.setRequestHeader("Content-Type", "application/json");
    xhttp.send(JSON_SEND);
}

function showEdit(e) {
	e.preventDefault();
	var editDiv = document.getElementById("editDiv");

	if (editDiv.style.display === "none") {
		editDiv.style.display = "block";
	}
	else {
		editDiv.style.display = "none";
	}

	select_event_id = e.target.id;
}

function addEvent(e) {
	e.preventDefault();
	const event = document.getElementById("fevent").value;
	const time = document.getElementById("ftime").value;
	const real_month = my_month + 1;
	const date = `${my_year}-${real_month}-${select_date}`;
	const token = document.getElementById("ftoken").value;
	var eventCategory = document.getElementById("eventCategory").value;
	
	if(eventCategory.length == 0){
		eventCategory = "None"
	}



	console.log(event);
	console.log(date);
	console.log(time);
	console.log(eventCategory);
	const data = { "event": event, "time": time, "token": token, "date": date, "eventCategory": eventCategory };
	


	fetch("addEvent.php", {
		method: 'POST',
		body: JSON.stringify(data),
		headers: { 'content-type': 'application/json' }
	})
		.then(response => response.json())
		.then(data => console.log(data.success ? "Add Success!" : "Not Logged In!"))
		.then(function() {

			document.getElementById("fevent").value = "";
			document.getElementById("ftime").value = "";
			document.getElementById("eventCategory").value = "";
			document.getElementById("All").checked = "checked";
			getEventList();
		})
		.catch(err => console.error(err));

}



function addGroupEvent(e){
	e.preventDefault();
	var numberOfUsers = prompt("Please enter the Total number of users you want to Share with","");
	numberOfUsers = parseInt(numberOfUsers);
	var user_count = 0;
	for(var i = 0; i < numberOfUsers; i++){
			var userid = prompt("Please enter the user's id you would like to share with","");
	console.log(userid);
	const event = document.getElementById("fevent").value;
	const time = document.getElementById("ftime").value;
	const real_month = my_month + 1;
	const date = `${my_year}-${real_month}-${select_date}`;
	const token = document.getElementById("ftoken").value;
	var groupEventCategory = document.getElementById("eventCategory").value;
	var shareSelf = false;
	if(groupEventCategory.length == 0){
		groupEventCategory = "None"
	}
	const data = { "userId": userid, "event": event, "time": time, "token": token, "date": date, "eventCategory": groupEventCategory };
	console.log("Here");
	console.log(data);

	

	fetch("addGroupEvent.php", {
		method: 'POST',
		body: JSON.stringify(data),
		headers: { 'content-type': 'application/json' }
	})
		.then(response => response.json())
		.then(data => {console.log(data.success ? "Add Success!" : "Cannot Share to Self!");
						if (data.success == false) {
							shareSelf = true;
						};})
		.then(function() {
			//add event to user.
	const event = document.getElementById("fevent").value;
	const time = document.getElementById("ftime").value;
	const real_month = my_month + 1;
	const date = `${my_year}-${real_month}-${select_date}`;
	const token = document.getElementById("ftoken").value;
	//const eventCategory = document.getElementById("eventCategory").value;

	if(groupEventCategory.length == 0){
		groupEventCategory = "None"
	}
	console.log(event);
	console.log(date);
	console.log(time);
	console.log(groupEventCategory);
	const data = { "event": event, "time": time, "token": token, "date": date, "eventCategory": groupEventCategory };

	console.log(shareSelf);

	console.log(`${user_count} is the num`);

	if (shareSelf != true && user_count == 0) {
		user_count++;
		fetch("addEvent.php", {
			method: 'POST',
			body: JSON.stringify(data),
			headers: { 'content-type': 'application/json' }
		})
			.then(response => response.json())
			.then(data => console.log(data.success ? "Add Success!" : "Not Logged In!"))
			.then(function() {

				document.getElementById("fevent").value = "";
				document.getElementById("ftime").value = "";
				document.getElementById("eventCategory").value = "";
				document.getElementById("All").checked = "checked";

				
			})
			.catch(err => console.error(err));
	}
	else if (shareSelf == true) {
		alert("Cannot Share to yourself!");
	}
	getEventList();
	})
	.catch(err => console.error(err));
			
	}


}
var share_event;
var share_time;
var share_category;
var userId;
function shareEvent(e){
	e.preventDefault();

	var userid = prompt("Please enter the user's id you would like to share with","");
	console.log("button id is:" + this.id);
	userId = userid;
	//store this id
	const real_month = my_month + 1;
	const date = `${my_year}-${real_month}-${select_date}`;

	const data = { "date": date, "event_id": this.id};

	const JSON_SEND = JSON.stringify(data)

	var xhttp = new XMLHttpRequest();
    xhttp.addEventListener("load", function(event) {
		
        if (this.status == 200) {
            var JSON_DATA = JSON.parse(this.responseText);
			var events = JSON_DATA.events;
      
			share_event = events[0].event;
      share_time = events[0].time;
			share_category = events[0].category;
			shareEvent2();
        }
    }, false);

    xhttp.open("POST", "getShareEvent.php", true);
    xhttp.setRequestHeader("Content-Type", "application/json");
    xhttp.send(JSON_SEND);
		
	}

	function shareEvent2(){
		const real_month = my_month + 1;
		const date = `${my_year}-${real_month}-${select_date}`;
		const token = document.getElementById("ftoken").value;


		const data = { "event": share_event, "time": share_time, "token": token, "date": date, "eventCategory": share_category ,"uid" : userId};
	


		fetch("ShareEvent.php", {
			method: 'POST',
			body: JSON.stringify(data),
			headers: { 'content-type': 'application/json' }
		})
			.then(response => response.json())
			.then(data => {console.log(data.success ? "Add Success!" : "Cannot Share to Self!");
							if (data.success == false) {
								shareSelf = true;
							};})
			.catch(err => console.error(err));
	}




function updateEvent(e) {

	e.preventDefault();
	const event = document.getElementById("efevent").value;
	const time = document.getElementById("eftime").value;
	const real_month = my_month + 1;
	const date = `${my_year}-${real_month}-${select_date}`;
	const token = document.getElementById("eftoken").value;
	var eventCategory = document.getElementById("EditeventCategory").value;
	
	if(eventCategory.length == 0){
		eventCategory = "None"
	}
	const data = { "event": event, "event_id": select_event_id, "time": time, "token": token, "date": date, "eventCategory": eventCategory };
	console.log(data);


	fetch("editEvent.php", {
		method: 'POST',
		body: JSON.stringify(data),
		headers: { 'content-type': 'application/json' }
	})
		.then(response => response.json())
		.then(data => console.log(data.success ? "Edit Success!" : "Not Logged In!"))
		.then(function() {
			document.getElementById("efevent").value = "";
			document.getElementById("eftime").value = "";
			document.getElementById("eventCategory").value = "";
			document.getElementById("editDiv").style.display="none";
			getEventList();
		})
		.catch(err => console.error(err));

}

function removeEvents(e) {
	e.preventDefault();

	const event_id = e.target.id;

	const data = { "event_id": event_id };
	console.log(data);


	fetch("removeEvent.php", {
		method: 'POST',
		body: JSON.stringify(data),
		headers: { 'content-type': 'application/json' }
	})
		.then(response => response.json())
		.then(data => console.log(data.success ? "Remove Success!" : "Not Logged In!"))
		.then(function() {
			getEventList();
		})
		.catch(err => console.error(err));
		document.getElementById("All").checked = "checked";

}

document.addEventListener("DOMContentLoaded", getEventList);
document.getElementById("addEvent").addEventListener("submit", addEvent, false);
document.getElementById("editEvent").addEventListener("submit", updateEvent, false);
document.getElementById("addGroupEvent").addEventListener("submit",addGroupEvent,false);

var All = document.getElementById("All");
All.addEventListener("click",selectCategory,false);

var Study = document.getElementById("Study");
Study.addEventListener("click",selectCategory,false);

var Work = document.getElementById("Work");
Work.addEventListener("click",selectCategory,false);
var Family = document.getElementById("Family");
Family.addEventListener("click",selectCategory,false);
var Social = document.getElementById("Social");
Social.addEventListener("click",selectCategory,false);



function selectCategory(){
	
	if(All.checked){
		
		getEventList();
	}

	else if(Study.checked){
	
		getCategoryEventList(Study.value);
	}

	else if(Work.checked){
		
		getCategoryEventList(Work.value);
	}

	else if(Family.checked){
		
		getCategoryEventList(Family.value);
	}

	else if(Social.checked){
		
		getCategoryEventList(Social.value);
	}
}
