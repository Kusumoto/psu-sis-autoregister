wget https://sonarsource.bintray.com/Distribution/sonar-scanner-cli/sonar-scanner-2.8.zip -O ~/scanner.zip
unzip ~/scanner.zip -d ~

cat << EOF > sonar-project.properties
  sonar.host.url=${SONAR_HOST_URL}
  sonar.projectKey=${SONAR_PROJECT_KEY}
  sonar.projectName=${SONAR_PROJECT_NAME}
  sonar.projectVersion=${SONAR_PROJECT_VERSION}
  sonar.sources=.
EOF

 ~/sonar-scanner-2.8/bin/sonar-scanner
