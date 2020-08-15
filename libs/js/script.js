$('#searchBtn').click(function(event) {
	event.preventDefault();
	$('#resultsContainer').html("");
	hideError();
	showLoadingImg();

	$.ajax({
		url: "libs/php/getWordInfo.php",
		type: 'POST',
		dataType: 'json',
		data: {
			word: $('#searchBox').val()
		},
		success: function(result) {
			hideLoadingImg()
			console.log(result);
			
			if (result.status.name == "ok") {
				let data = result['data'];
				let keys = Object.keys(data);
				let word = result.word;

				keys.forEach((key, index) => {

					var result = document.createElement('div');
					$(result).addClass('result');

					var title = document.createElement('h1');
					$(title).addClass('d-inline-block').addClass('text-capitalize');
					title.appendChild(document.createTextNode(word))
					result.appendChild(title);

					var partOfSpeech = document.createElement('p');
					$(partOfSpeech).addClass('text-muted').addClass('ml-3').addClass('d-inline-block');
					partOfSpeech.appendChild(document.createTextNode(key))
					result.appendChild(partOfSpeech);

					var subTitle = document.createElement('h4');
					subTitle.appendChild(document.createTextNode("Definition of "));
					var italic = document.createElement('span');
					$(italic).addClass('font-italic');
					italic.appendChild(document.createTextNode(word));
					subTitle.appendChild(italic);
					result.appendChild(subTitle);

					var definitions = document.createElement('div');
					$(definitions).addClass('definitionContainer');
					var count = 1
					for(var i = 0; i < data[key].length; i++){
						var definition = document.createElement('p');
						definition.appendChild(document.createTextNode(count + ". " +data[key][i]));
						definitions.appendChild(definition);
						count++;
					}
					result.appendChild(definitions);

					$('#resultsContainer').append(result);
				});
			}	
			else {
				showError(result.message);
			}
		},
		error: function(jqXHR, textStatus, errorThrown) {
			hideLoadingImg()
			showError(textStatus+ ": " + errorThrown);
		},
	}); 


});

// helper functions
function showError(message){
	console.log('error')
	$('#errorAlert').html(message);
	$('#errorAlert').show();
}

function hideError(){
	$('#errorAlert').html("");
	$('#errorAlert').hide();
}

function showLoadingImg(){
	$('#spinnerContainer').show();
}

function hideLoadingImg(){
	$('#spinnerContainer').hide();
}