wget https://sonarsource.bintray.com/Distribution/sonar-scanner-cli/sonar-scanner-2.8.zip-O ~/scanner.zip
unzip ~/scanner.zip -d ~

if [ "$TRAVIS_PULL_REQUEST" != "false" ] && [ -n "${GITHUB_TOKEN:-}" ]; then
  cat << EOF > sonar-project.properties
  sonar.host.url=https://sonar.kusumotolab.com
  sonar.projectKey=com.kusumotolab.psuautoreg:master
  sonar.projectName=psu-sis-autoregister
  sonar.projectVersion=1.0
  sonar.sources=.
EOF

 ~/sonar-scanner-2.8/bin/sonar-scanner
