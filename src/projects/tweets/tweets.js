import data from "./data.json"

var tweets = {
  'project_name': 'Tweets Project',
  'direction': 'ltr',
  'data': data,
  'text_key': 'text',
  'per_page' : 25,
  'categories': {
                'type':'single',
                'options':{'pos': 'Positive', 'neg': 'Negative', 'neu':'Neutral'}
              },
  'highlight_categories': {
                          'loc': 'Location',
                          'org': 'Organization',
                          'per':'Person'
                        },
}

export default tweets;
