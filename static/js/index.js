let edit = false;
$('#mode').val(edit);

$(document).ready(function () {
	$('#taskForm').trigger('reset');
	showTasks();
});

// ###############################################################
// FUNCTION INSERT TASKS

$('#taskForm').on('submit', function(e){
	e.preventDefault();
	console.log(edit);

		if(edit){
			let title = $('#title');
			let priority = $('#priority');
			let description = $('#description');
			let taskId = document.getElementById('btnSend').getAttribute('idTask');
			let urlUpdate = "update.php";

		const datosUpdate = {
			id: taskId,
			title: title.val(),
			priority: priority.val(),
			description: description.val()
		};


		fetch(urlUpdate, {
			method: 'PUT',
			body: JSON.stringify(datosUpdate),
			headers: {
				'Content-Type':'application/json'
			}
		})
		.then(res => res.json())
		.then(function(response){
			let messagesTemplate = `<div class="alert alert-dismissible alert-info" role='alert' >
			<strong>${response.message}</strong>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>`;
		$('#messages').html(messagesTemplate);
		})
		.then(response => showTasks());
		edit = false;
		modoNormal()
		console.log(datosUpdate);
		$('#taskForm').trigger('reset');

		} else{

		let title = $('#title').val();
		let priority = $('#priority').val();
		let description = $('#description').val();

		let url = "insert.php";

		let datos = {
			title,
			priority,
			description
		};

		$('#taskForm').trigger('reset');

		fetch(url, {
			method: 'POST',
			body: JSON.stringify(datos),
			headers: {
				'Content-Type': 'application/json'
			}
		})
		.then(res => res.json())
		.then(function(response){
			let messagesTemplate = `<div class="alert alert-dismissible alert-info" role='alert' >
			<strong>${response.message}</strong>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>`;
		$('#messages').html(messagesTemplate);
		})
		.then(response => showTasks());
	}
	$('#IDTASK').html('');
});

function modoEditar(){
	$('#card').addClass('bg-dark');
}

function modoNormal(){
	$('#card').removeClass('bg-dark');
}

// ###############################################################
// FUNCTION EDIT TASKS

$(document).on('click', '.task-edit', function(){
	edit = true;

		let taskID = this.getAttribute('id');
		let title = $('#title');
		let priority = $('#priority');
		let description = $('#description');
		$('#IDTASK').html(taskID);
		let titleHTML = this.parentElement.parentElement.children[0].children[2].firstChild.data;
		let priorityHTML = this.parentElement.parentElement.children[1].children[0].children[0].lastChild.data;
		let descriptionHTML = this.parentElement.parentElement.children[1].children[1].firstChild.data;
		title.val(titleHTML);
		priority.val(priorityHTML);
		description.val(descriptionHTML);
		document.getElementById('btnSend').setAttribute('idTask', taskID);
		modoEditar();

});
// ###############################################################
// FUNCTION SEARCH TASKS

$('#search').keyup( function(){
	let search = $('#search').val();
	console.log(search);
	let url = `search.php?q=${search}`;
	fetch(url, {
		method: 'POST',
		body: JSON.stringify(search),
		headers: {
			'Content-Type':'application/json'
		}
	})
	.then(res => res.json())
	.then(function(json){

	let template = '';
	let badgeColor ='';
	
	if(json.message == "Task no found"){
		template = `
			<div class="card card-body my-4 text-center">
				<h5>${json.message+ " '" + search+ "' "}</h5>
			</div>
		`;
		$('#cards').html(template);
	} else{

		json.forEach(json => {

				if(json.priority == 'high'){
			badgeColor = "danger";
		} else if(json.priority == 'medium'){
			badgeColor = "warning";
		} else if(json.priority == 'low'){
			badgeColor = "success";
		} else{
			badgeColor = "dark";
		}

		template+= `
			<div class="col-md-4 pt-4 px-3 text-center">
				<div class="card">
					<div class="card-header bg-dark text-light">
						<button id="${json.id}" class="btn btn-secondary btn-sm text-center task-edit">e</button>
						 	<span class="badge badge-pill badge-light">${json.id}</span>
							<span>${json.title}</span>
						<button id="${json.id}" class="btn btn-danger btn-sm text-center task-delete">x</button>
					</div>
					<div class="card-body">
						<div class="form-group"> <span class="badge badge-pill badge-${badgeColor} btn-block font-span">${json.priority}</span> </div>
						<div class="form-group">${json.description}</div>
					</div>
				</div>
			</div>
		`;

		console.log(json);

		$('#cards').html(template);

			});

	}
			
	});
});

// ###############################################################
// FUNCTION DELETE TASKS

$(document).on('click', '.task-delete', function(){
	let taskID = this.getAttribute('id');
	let deleteData = {
		id: taskID
	};
	let urlDelete = "delete.php";

	fetch(urlDelete, {
		method: 'POST',
		body: JSON.stringify(deleteData),
		headers: {
			'Content-Type': 'application/json'
		}
	})
	.then(res => res.json())
	.then(function(response){
		let messagesTemplate = `<div class="alert alert-dismissible alert-info" role='alert' >
			<strong>${response.message}</strong>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>`;
		$('#messages').html(messagesTemplate);
	})
	.then(response => showTasks());
});


// ################################################################
// FUNCION PARA MOSTRAR LAS TAREAS

function showTasks(){

let url = "select.php";
fetch(url)
.then(function(response) {
 	return response.json();
 })
.then(function(json) {
	let template = '';
	let badgeColor ='';
	let taskCounter = 0;
	json.forEach(json => {
		taskCounter++;
		
		if(json.priority == 'high'){
			badgeColor = "danger";
		} else if(json.priority == 'medium'){
			badgeColor = "warning";
		} else if(json.priority == 'low'){
			badgeColor = "success";
		} else{
			badgeColor = "dark";
		}

		template+= `
			<div class="col-md-4 pt-4 px-3 text-center">
				<div class="card">
					<div class="card-header bg-dark text-light">
						<button id="${json.id}" class="btn btn-secondary btn-sm text-center task-edit">e</button>
						 	<span class="badge badge-pill badge-light">${json.id}</span>
							<span>${json.title}</span>
						<button id="${json.id}" class="btn btn-danger btn-sm text-center task-delete">x</button>
					</div>
					<div class="card-body">
						<div class="form-group"> <span class="badge badge-pill badge-${badgeColor} btn-block font-span">${json.priority}</span> </div>
						<div class="form-group">${json.description}</div>
					</div>
				</div>
			</div>
		`;
		$('#cards').html(template);
	})
	$('#count').html(taskCounter);
});
}