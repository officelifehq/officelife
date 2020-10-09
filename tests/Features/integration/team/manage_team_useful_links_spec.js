describe('Team - Useful link management', function () {
  it('should let you add a useful link as an administrator', function () {
    cy.loginLegacy()

    cy.createCompany()
    cy.createTeam('product')

    cy.wait(1000)

    cy.visit('/1/teams/1')

    // add a link with a label of the URL type
    cy.get('[data-cy=useful-link-add-new-link]').click()
    cy.get('[data-cy=useful-link-type]').select('url')
    cy.get('input[name=label]').type('Name of the url')
    cy.get('input[name=url]').type('https://officelife.io')
    cy.get('[data-cy=useful-link-submit-button]').click()

    cy.get('[data-cy=team-useful-link-1]').contains('Name of the url')
    cy.get('[data-cy=team-useful-link-logo-url-1]').should('exist')
    cy.hasAuditLog('Added a link called Name of the url to the team called', '/1/teams/1')
    cy.hasTeamLog('Added a link called Name of the url', '/1/teams/1')

    // add another link without label of the URL type
    cy.get('[data-cy=useful-link-add-new-link]').click()
    cy.get('[data-cy=useful-link-type]').select('url')
    cy.get('input[name=url]').type('https://officelife.io')
    cy.get('[data-cy=useful-link-submit-button]').click()

    cy.get('[data-cy=team-useful-link-2]').contains('https://officelife.io')
    cy.get('[data-cy=team-useful-link-logo-url-2]').should('exist')
    cy.hasAuditLog('Added a link called https://officelife.io to the team called', '/1/teams/1')
    cy.hasTeamLog('Added a link called https://officelife.io', '/1/teams/1')

    // remove the link
    cy.get('[data-cy=useful-link-edit-links]').click()
    cy.get('[data-cy=useful-link-exit-edit-link]').click()
    cy.get('[data-cy=useful-link-edit-links]').click()
    cy.get('[data-cy=team-useful-link-2-destroy]').click()

    cy.get('[data-cy=team-useful-link-2]').should('not.exist')
    cy.hasAuditLog('Removed the link called https://officelife.io', '/1/teams/1')
    cy.hasTeamLog('Destroyed the link called https://officelife.io', '/1/teams/1')

    // add a link with a label of the Slack type
    cy.get('[data-cy=useful-link-add-new-link]').click()
    cy.get('[data-cy=useful-link-type]').select('slack')
    cy.get('input[name=label]').type('Slack channel')
    cy.get('input[name=url]').type('https://slack.com/officelife')
    cy.get('[data-cy=useful-link-submit-button]').click()

    cy.get('[data-cy=team-useful-link-3]').contains('Slack channel')
    cy.get('[data-cy=team-useful-link-logo-slack-3]').should('exist')
    cy.hasAuditLog('Added a link called Slack channel to the team called', '/1/teams/1')
    cy.hasTeamLog('Added a link called Slack channel', '/1/teams/1')

    // add another link without label of the Slack type
    cy.get('[data-cy=useful-link-add-new-link]').click()
    cy.get('[data-cy=useful-link-type]').select('slack')
    cy.get('input[name=url]').type('https://slack.com/officelife')
    cy.get('[data-cy=useful-link-submit-button]').click()

    cy.get('[data-cy=team-useful-link-4]').contains('https://slack.com/officelife')
    cy.get('[data-cy=team-useful-link-logo-slack-4]').should('exist')
    cy.hasAuditLog('Added a link called https://slack.com/officelife to the team called', '/1/teams/1')
    cy.hasTeamLog('Added a link called https://slack.com/officelife', '/1/teams/1')

    // add a link with a label of the Email type
    cy.get('[data-cy=useful-link-add-new-link]').click()
    cy.get('[data-cy=useful-link-type]').select('email')
    cy.get('input[name=label]').type('Contact support')
    cy.get('input[name=url]').type('dwight@dundermifflin.com')
    cy.get('[data-cy=useful-link-submit-button]').click()

    cy.get('[data-cy=team-useful-link-5]').contains('Contact support')
    cy.get('[data-cy=team-useful-link-logo-email-5]').should('exist')
    cy.hasAuditLog('Added a link called Contact support to the team called', '/1/teams/1')
    cy.hasTeamLog('Added a link called Contact support', '/1/teams/1')

    // add another link without label of the Email type
    cy.get('[data-cy=useful-link-add-new-link]').click()
    cy.get('[data-cy=useful-link-type]').select('email')
    cy.get('input[name=url]').type('dwight@dundermifflin.com')
    cy.get('[data-cy=useful-link-submit-button]').click()

    cy.get('[data-cy=team-useful-link-6]').contains('dwight@dundermifflin.com')
    cy.get('[data-cy=team-useful-link-logo-email-6]').should('exist')
    cy.hasAuditLog('Added a link called dwight@dundermifflin.com to the team called', '/1/teams/1')
    cy.hasTeamLog('Added a link called dwight@dundermifflin.com', '/1/teams/1')
  })

  it('should let you add a useful link as an HR', function () {
    cy.loginLegacy()

    cy.createCompany()
    cy.createTeam('product')

    cy.wait(1000)

    cy.changePermission(1, 200)

    cy.visit('/1/teams/1')

    // add a link with a label of the URL type
    cy.get('[data-cy=useful-link-add-new-link]').click()
    cy.get('[data-cy=useful-link-type]').select('url')
    cy.get('input[name=label]').type('Name of the url')
    cy.get('input[name=url]').type('https://officelife.io')
    cy.get('[data-cy=useful-link-submit-button]').click()

    cy.get('[data-cy=team-useful-link-1]').contains('Name of the url')
    cy.get('[data-cy=team-useful-link-logo-url-1]').should('exist')
    cy.hasTeamLog('Added a link called Name of the url', '/1/teams/1')

    // add another link without label of the URL type
    cy.get('[data-cy=useful-link-add-new-link]').click()
    cy.get('[data-cy=useful-link-type]').select('url')
    cy.get('input[name=url]').type('https://officelife.io')
    cy.get('[data-cy=useful-link-submit-button]').click()

    cy.get('[data-cy=team-useful-link-2]').contains('https://officelife.io')
    cy.get('[data-cy=team-useful-link-logo-url-2]').should('exist')
    cy.hasTeamLog('Added a link called https://officelife.io', '/1/teams/1')

    // remove the link
    cy.get('[data-cy=useful-link-edit-links]').click()
    cy.get('[data-cy=useful-link-exit-edit-link]').click()
    cy.get('[data-cy=useful-link-edit-links]').click()
    cy.get('[data-cy=team-useful-link-2-destroy]').click()

    cy.get('[data-cy=team-useful-link-2]').should('not.exist')
    cy.hasTeamLog('Destroyed the link called https://officelife.io', '/1/teams/1')

    // add a link with a label of the Slack type
    cy.get('[data-cy=useful-link-add-new-link]').click()
    cy.get('[data-cy=useful-link-type]').select('slack')
    cy.get('input[name=label]').type('Slack channel')
    cy.get('input[name=url]').type('https://slack.com/officelife')
    cy.get('[data-cy=useful-link-submit-button]').click()

    cy.get('[data-cy=team-useful-link-3]').contains('Slack channel')
    cy.get('[data-cy=team-useful-link-logo-slack-3]').should('exist')
    cy.hasTeamLog('Added a link called Slack channel', '/1/teams/1')

    // add another link without label of the Slack type
    cy.get('[data-cy=useful-link-add-new-link]').click()
    cy.get('[data-cy=useful-link-type]').select('slack')
    cy.get('input[name=url]').type('https://slack.com/officelife')
    cy.get('[data-cy=useful-link-submit-button]').click()

    cy.get('[data-cy=team-useful-link-4]').contains('https://slack.com/officelife')
    cy.get('[data-cy=team-useful-link-logo-slack-4]').should('exist')
    cy.hasTeamLog('Added a link called https://slack.com/officelife', '/1/teams/1')

    // add a link with a label of the Email type
    cy.get('[data-cy=useful-link-add-new-link]').click()
    cy.get('[data-cy=useful-link-type]').select('email')
    cy.get('input[name=label]').type('Contact support')
    cy.get('input[name=url]').type('dwight@dundermifflin.com')
    cy.get('[data-cy=useful-link-submit-button]').click()

    cy.get('[data-cy=team-useful-link-5]').contains('Contact support')
    cy.get('[data-cy=team-useful-link-logo-email-5]').should('exist')
    cy.hasTeamLog('Added a link called Contact support', '/1/teams/1')

    // add another link without label of the Email type
    cy.get('[data-cy=useful-link-add-new-link]').click()
    cy.get('[data-cy=useful-link-type]').select('email')
    cy.get('input[name=url]').type('dwight@dundermifflin.com')
    cy.get('[data-cy=useful-link-submit-button]').click()

    cy.get('[data-cy=team-useful-link-6]').contains('dwight@dundermifflin.com')
    cy.get('[data-cy=team-useful-link-logo-email-6]').should('exist')
    cy.hasTeamLog('Added a link called dwight@dundermifflin.com', '/1/teams/1')
  })

  it('should not let you add a useful link as an external normal employee', function () {
    cy.loginLegacy()

    cy.createCompany()
    cy.createTeam('product')

    cy.wait(1000)

    cy.changePermission(1, 300)

    cy.visit('/1/teams/1')

    // add a link with a label of the URL type
    cy.get('[data-cy=useful-link-add-new-link]').should('not.exist')
  })

  it('should let you add a useful link as a team member', function () {
    cy.loginLegacy()

    cy.createCompany()
    cy.createTeam('product')

    cy.wait(1000)

    cy.assignEmployeeToTeam(1, 1)
    cy.changePermission(1, 300)

    cy.visit('/1/teams/1')

    // add a link with a label of the URL type
    cy.get('[data-cy=useful-link-add-new-link]').click()
    cy.get('[data-cy=useful-link-type]').select('url')
    cy.get('input[name=label]').type('Name of the url')
    cy.get('input[name=url]').type('https://officelife.io')
    cy.get('[data-cy=useful-link-submit-button]').click()

    cy.get('[data-cy=team-useful-link-1]').contains('Name of the url')
    cy.get('[data-cy=team-useful-link-logo-url-1]').should('exist')

    // add another link without label of the URL type
    cy.get('[data-cy=useful-link-add-new-link]').click()
    cy.get('[data-cy=useful-link-type]').select('url')
    cy.get('input[name=url]').type('https://officelife.io')
    cy.get('[data-cy=useful-link-submit-button]').click()

    cy.get('[data-cy=team-useful-link-2]').contains('https://officelife.io')
    cy.get('[data-cy=team-useful-link-logo-url-2]').should('exist')

    // remove the link
    cy.get('[data-cy=useful-link-edit-links]').click()
    cy.get('[data-cy=useful-link-exit-edit-link]').click()
    cy.get('[data-cy=useful-link-edit-links]').click()
    cy.get('[data-cy=team-useful-link-2-destroy]').click()

    cy.get('[data-cy=team-useful-link-2]').should('not.exist')

    // add a link with a label of the Slack type
    cy.get('[data-cy=useful-link-add-new-link]').click()
    cy.get('[data-cy=useful-link-type]').select('slack')
    cy.get('input[name=label]').type('Slack channel')
    cy.get('input[name=url]').type('https://slack.com/officelife')
    cy.get('[data-cy=useful-link-submit-button]').click()

    cy.get('[data-cy=team-useful-link-3]').contains('Slack channel')
    cy.get('[data-cy=team-useful-link-logo-slack-3]').should('exist')

    // add another link without label of the Slack type
    cy.get('[data-cy=useful-link-add-new-link]').click()
    cy.get('[data-cy=useful-link-type]').select('slack')
    cy.get('input[name=url]').type('https://slack.com/officelife')
    cy.get('[data-cy=useful-link-submit-button]').click()

    cy.get('[data-cy=team-useful-link-4]').contains('https://slack.com/officelife')
    cy.get('[data-cy=team-useful-link-logo-slack-4]').should('exist')

    // add a link with a label of the Email type
    cy.get('[data-cy=useful-link-add-new-link]').click()
    cy.get('[data-cy=useful-link-type]').select('email')
    cy.get('input[name=label]').type('Contact support')
    cy.get('input[name=url]').type('dwight@dundermifflin.com')
    cy.get('[data-cy=useful-link-submit-button]').click()

    cy.get('[data-cy=team-useful-link-5]').contains('Contact support')
    cy.get('[data-cy=team-useful-link-logo-email-5]').should('exist')

    // add another link without label of the Email type
    cy.get('[data-cy=useful-link-add-new-link]').click()
    cy.get('[data-cy=useful-link-type]').select('email')
    cy.get('input[name=url]').type('dwight@dundermifflin.com')
    cy.get('[data-cy=useful-link-submit-button]').click()

    cy.get('[data-cy=team-useful-link-6]').contains('dwight@dundermifflin.com')
    cy.get('[data-cy=team-useful-link-logo-email-6]').should('exist')
  })
})
