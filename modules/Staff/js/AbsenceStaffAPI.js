function AbsenceStaffAPI(rootnode)
{
	var thisAPI = this;
	thisAPI.RootNode = rootnode;
	thisAPI.GetEndpoint = function(endpoint,data,asPromise)
	{
		var fullAPIEndpoint = thisAPI.RootNode + "AbsenceStaff/" + endpoint + ".php";

		if(asPromise == true)
		{
			return $.ajax({
				url: fullAPIEndpoint,
				method: "POST",
				async: true,
				data: data
			});
		}
		else
		{
			var result = null;
			$.get(fullAPIEndpoint,function(data){result = data;});
			return result;
		}
	}

	thisAPI.GetAll = function(asAsync)
	{
		if(asAsync == false)
		{
			return thisAPI.GetEndpoint("GetAll",null,false);
		}
		else
		{
			return thisAPI.GetEndpoint("GetAll",null,false)
				.done(function(data)
				{
					return JSON.parse(data);	
				});
		}
	}
}
