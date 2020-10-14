describe('Project - project links', function () {
  it('should let an employee create a project as administrator', function () {
    cy.loginLegacy()

    cy.createCompany()

    // make sure we can create a project with only the name of the project
    cy.createProject(1, 'project 1')

    // add project links
    cy.get('[data-cy=add-new-link]').click()
    cy.get('[data-cy=link-type]').select('url')
    cy.get('input[name=label]').type('Name of the url')
    cy.get('input[name=url]').type('https://officelife.io')
    cy.get('[data-cy=link-submit-button]').click()

    cy.get('[data-cy=project-link-1]').contains('Name of the url')
    cy.get('[data-cy=project-link-logo-url-1]').should('exist')
    cy.hasAuditLog('Added the link called', '/1/projects/1')

    // add another link without label of the URL type
    cy.get('[data-cy=add-new-link]').click()
    cy.get('[data-cy=link-type]').select('url')
    cy.get('input[name=url]').type('https://officelife.io')
    cy.get('[data-cy=link-submit-button]').click()

    cy.get('[data-cy=project-link-2]').should('exist')
    cy.get('[data-cy=project-link-logo-url-2]').should('exist')

    // remove the link
    cy.get('[data-cy=edit-links]').click()
    cy.get('[data-cy=exit-edit-link]').click()
    cy.get('[data-cy=edit-links]').click()
    cy.get('[data-cy=project-link-2-destroy]').click()

    cy.get('[data-cy=team-useful-link-2]').should('not.exist')
    cy.hasAuditLog('Deleted the link called', '/1/projects/1')

    // add a link with a label of the Slack type
    cy.get('[data-cy=add-new-link]').click()
    cy.get('[data-cy=link-type]').select('slack')
    cy.get('input[name=label]').type('Slack channel')
    cy.get('input[name=url]').type('https://slack.com/officelife')
    cy.get('[data-cy=link-submit-button]').click()

    cy.get('[data-cy=project-link-3]').contains('Slack channel')
    cy.get('[data-cy=project-link-logo-slack-3]').should('exist')
    cy.hasAuditLog('Added the link called', '/1/projects/1')

    // add a link of the Slack type without a label
    cy.get('[data-cy=add-new-link]').click()
    cy.get('[data-cy=link-type]').select('slack')
    cy.get('input[name=url]').type('https://slack.com/officelife')
    cy.get('[data-cy=link-submit-button]').click()

    cy.get('[data-cy=project-link-4]').contains('https://slack.com/officelife')
    cy.get('[data-cy=project-link-logo-slack-4]').should('exist')

    // add a link with a label of the email type
    cy.get('[data-cy=add-new-link]').click()
    cy.get('[data-cy=link-type]').select('email')
    cy.get('input[name=label]').type('Contact support')
    cy.get('input[name=url]').type('dwight@dundermifflin.com')
    cy.get('[data-cy=link-submit-button]').click()

    cy.get('[data-cy=project-link-5]').contains('Contact support')
    cy.get('[data-cy=project-link-logo-email-5]').should('exist')

    // add a link with a label of the email type, without a label
    cy.get('[data-cy=add-new-link]').click()
    cy.get('[data-cy=link-type]').select('email')
    cy.get('input[name=url]').type('dwight@dundermifflin.com')
    cy.get('[data-cy=link-submit-button]').click()
    cy.get('[data-cy=project-link-logo-email-6]').should('exist')
  })

  it('should let an employee create a project as HR', function () {
    cy.loginLegacy()

    cy.createCompany()

    cy.changePermission(1, 200)

    // make sure we can create a project with only the name of the project
    cy.createProject(1, 'project 1')

    // add project links
    cy.get('[data-cy=add-new-link]').click()
    cy.get('[data-cy=link-type]').select('url')
    cy.get('input[name=label]').type('Name of the url')
    cy.get('input[name=url]').type('https://officelife.io')
    cy.get('[data-cy=link-submit-button]').click()

    cy.get('[data-cy=project-link-1]').contains('Name of the url')
    cy.get('[data-cy=project-link-logo-url-1]').should('exist')
    cy.hasAuditLog('Added the link called', '/1/projects/1')

    // add another link without label of the URL type
    cy.get('[data-cy=add-new-link]').click()
    cy.get('[data-cy=link-type]').select('url')
    cy.get('input[name=url]').type('https://officelife.io')
    cy.get('[data-cy=link-submit-button]').click()

    cy.get('[data-cy=project-link-2]').should('exist')
    cy.get('[data-cy=project-link-logo-url-2]').should('exist')

    // remove the link
    cy.get('[data-cy=edit-links]').click()
    cy.get('[data-cy=exit-edit-link]').click()
    cy.get('[data-cy=edit-links]').click()
    cy.get('[data-cy=project-link-2-destroy]').click()

    cy.get('[data-cy=team-useful-link-2]').should('not.exist')
    cy.hasAuditLog('Deleted the link called', '/1/projects/1')

    // add a link with a label of the Slack type
    cy.get('[data-cy=add-new-link]').click()
    cy.get('[data-cy=link-type]').select('slack')
    cy.get('input[name=label]').type('Slack channel')
    cy.get('input[name=url]').type('https://slack.com/officelife')
    cy.get('[data-cy=link-submit-button]').click()

    cy.get('[data-cy=project-link-3]').contains('Slack channel')
    cy.get('[data-cy=project-link-logo-slack-3]').should('exist')
    cy.hasAuditLog('Added the link called', '/1/projects/1')

    // add a link of the Slack type without a label
    cy.get('[data-cy=add-new-link]').click()
    cy.get('[data-cy=link-type]').select('slack')
    cy.get('input[name=url]').type('https://slack.com/officelife')
    cy.get('[data-cy=link-submit-button]').click()

    cy.get('[data-cy=project-link-4]').contains('https://slack.com/officelife')
    cy.get('[data-cy=project-link-logo-slack-4]').should('exist')

    // add a link with a label of the email type
    cy.get('[data-cy=add-new-link]').click()
    cy.get('[data-cy=link-type]').select('email')
    cy.get('input[name=label]').type('Contact support')
    cy.get('input[name=url]').type('dwight@dundermifflin.com')
    cy.get('[data-cy=link-submit-button]').click()

    cy.get('[data-cy=project-link-5]').contains('Contact support')
    cy.get('[data-cy=project-link-logo-email-5]').should('exist')

    // add a link with a label of the email type, without a label
    cy.get('[data-cy=add-new-link]').click()
    cy.get('[data-cy=link-type]').select('email')
    cy.get('input[name=url]').type('dwight@dundermifflin.com')
    cy.get('[data-cy=link-submit-button]').click()
    cy.get('[data-cy=project-link-logo-email-6]').should('exist')
  })

  it('should let an employee create a project as normal user', function () {
    cy.loginLegacy()

    cy.createCompany()

    cy.changePermission(1, 300)

    // make sure we can create a project with only the name of the project
    cy.createProject(1, 'project 1')

    // add project links
    cy.get('[data-cy=add-new-link]').click()
    cy.get('[data-cy=link-type]').select('url')
    cy.get('input[name=label]').type('Name of the url')
    cy.get('input[name=url]').type('https://officelife.io')
    cy.get('[data-cy=link-submit-button]').click()

    cy.get('[data-cy=project-link-1]').contains('Name of the url')
    cy.get('[data-cy=project-link-logo-url-1]').should('exist')
    cy.hasAuditLog('Added the link called', '/1/projects/1')

    // add another link without label of the URL type
    cy.get('[data-cy=add-new-link]').click()
    cy.get('[data-cy=link-type]').select('url')
    cy.get('input[name=url]').type('https://officelife.io')
    cy.get('[data-cy=link-submit-button]').click()

    cy.get('[data-cy=project-link-2]').should('exist')
    cy.get('[data-cy=project-link-logo-url-2]').should('exist')

    // remove the link
    cy.get('[data-cy=edit-links]').click()
    cy.get('[data-cy=exit-edit-link]').click()
    cy.get('[data-cy=edit-links]').click()
    cy.get('[data-cy=project-link-2-destroy]').click()

    cy.get('[data-cy=team-useful-link-2]').should('not.exist')
    cy.hasAuditLog('Deleted the link called', '/1/projects/1')

    // add a link with a label of the Slack type
    cy.get('[data-cy=add-new-link]').click()
    cy.get('[data-cy=link-type]').select('slack')
    cy.get('input[name=label]').type('Slack channel')
    cy.get('input[name=url]').type('https://slack.com/officelife')
    cy.get('[data-cy=link-submit-button]').click()

    cy.get('[data-cy=project-link-3]').contains('Slack channel')
    cy.get('[data-cy=project-link-logo-slack-3]').should('exist')
    cy.hasAuditLog('Added the link called', '/1/projects/1')

    // add a link of the Slack type without a label
    cy.get('[data-cy=add-new-link]').click()
    cy.get('[data-cy=link-type]').select('slack')
    cy.get('input[name=url]').type('https://slack.com/officelife')
    cy.get('[data-cy=link-submit-button]').click()

    cy.get('[data-cy=project-link-4]').contains('https://slack.com/officelife')
    cy.get('[data-cy=project-link-logo-slack-4]').should('exist')

    // add a link with a label of the email type
    cy.get('[data-cy=add-new-link]').click()
    cy.get('[data-cy=link-type]').select('email')
    cy.get('input[name=label]').type('Contact support')
    cy.get('input[name=url]').type('dwight@dundermifflin.com')
    cy.get('[data-cy=link-submit-button]').click()

    cy.get('[data-cy=project-link-5]').contains('Contact support')
    cy.get('[data-cy=project-link-logo-email-5]').should('exist')

    // add a link with a label of the email type, without a label
    cy.get('[data-cy=add-new-link]').click()
    cy.get('[data-cy=link-type]').select('email')
    cy.get('input[name=url]').type('dwight@dundermifflin.com')
    cy.get('[data-cy=link-submit-button]').click()
    cy.get('[data-cy=project-link-logo-email-6]').should('exist')
  })
})
