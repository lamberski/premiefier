require('frontkit')(require('gulp'), {
  "source": "source",
  "targets": [
    {
      "path": "public",
      "tasks": [
        "templates",
        "scripts",
        "styles",
        "images",
        "icons",
        "files"
      ]
    }
  ],
  'deploy': {}
})
