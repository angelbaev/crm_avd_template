			function sendRequest(url, params) {
				$.ajax({
					type : 'POST',
					url : ''+url+'',
					dataType : 'json',
					cache: false,
					data: params,
					success : function(data){
						if (data['success']) {
							alert(data['success']);
						}
						if (data['error']) {
							alert(data['error']);
						}
					},
					error : function(XMLHttpRequest, textStatus, errorThrown) {
				    alert('Error: '+errorThrown); 
					}
				});
			}
