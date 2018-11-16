template.helper('parseJSON',function(content)
{
	switch($.type(content))
	{
		case "object":
		case "array":
		{
			return content;
		}
		break;

		case "string":
		{
			if($.trim(content))
			{
				return $.parseJSON(content);
			}
		}
		break;
	}
	return null;
});
template.helper('encodeJSON',function(content){return content.replace(/"/g,'\\"');});
template.helper('jsonToString',function(json){
	switch($.type(json))
	{
		case "object":
		{
			var itemArray = [];
			for(var index in json)
			{
				itemArray.push('"' + index + '":"' + json[index] + '"');
			}
			return '{'+itemArray.join(",")+'}';
		}
		break;

		case "array":
		{
			var itemArray = [];
			for(var index in json)
			{
				itemArray.push('"'+json[index]+'"');
			}
			return '['+itemArray.join(",")+']';
		}
		break;

		default:
		{
			return json;
		}
		break;
	}
});
template.helper('jQuery',function()
{
	return jQuery;
});