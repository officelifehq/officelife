// Create a user
Cypress.Commands.add('login', (role) => {
  cy.exec('php artisan setup:frontendtesting -vvv')

  cy.visit('/login')

  cy.get('input[name=email]').type('admin@admin.com')
  cy.get('input[name=password]').type('admin')

  cy.get('button[type=submit]').click()

  cy.wait(1000)

  cy.url().should('include', '/home')
})

Cypress.Commands.add('logout', () => {
  cy.get('[data-cy=header-menu]').click()
  cy.get('[data-cy=logout-button]').click()
})

// Create a company called "Dunder Mifflin"
Cypress.Commands.add('createCompany', () => {
  cy.get('[data-cy=create-company-blank-state]').click()

  cy.url().should('include', '/company/create')

  cy.get('input[name=name]').type('Dunder Mifflin')
  cy.get('[data-cy=create-company-submit]').click()

  cy.wait(500)
})

// Create a team
Cypress.Commands.add('createTeam', (productName) => {
  cy.visit('/1/account')

  cy.get('[data-cy=team-admin-link]').click()
  cy.get('[data-cy=add-team-button]').click()

  cy.get('input[name=name]').type(productName)

  cy.get('[data-cy=submit-add-team-button]').click()
})

// Create an employee status
Cypress.Commands.add('createEmployeeStatus', (status) => {
  cy.visit('/1/account')

  cy.get('[data-cy=employee-statuses-admin-link]').click()
  cy.get('[data-cy=add-status-button]').click()

  cy.get('[data-cy=add-title-input]').type(status)

  cy.get('[data-cy=modal-add-cta]').click()
})

// Create an employee
Cypress.Commands.add('createEmployee', (firstname, lastname, email, permission, sendEmail) => {
  cy.visit('/1/account')

  cy.get('[data-cy=employee-admin-link]').click()
  cy.url().should('include', '/account/employees')

  cy.get('[data-cy=add-employee-button]').click()

  cy.get('input[name=first_name]').type(firstname)
  cy.get('input[name=last_name]').type(lastname)
  cy.get('input[name=email]').type(email)

  if (permission === 'admin') {
    cy.get('[type="radio"]').first().check()
  }

  if (permission === 'hr') {
    cy.get('[type="radio"]').check(['200'])
  }

  if (permission === 'user') {
    cy.get('[type="radio"]').check(['300'])
  }

  if (sendEmail === true) {
    cy.get('[data-cy=send-email]').check()
  }

  cy.get('[data-cy=submit-add-employee-button]').click()
})

// Assert that the page can be visited by a user with the right permission level
Cypress.Commands.add('canAccess', (url, permission, textToSee) => {
  cy.changePermission(1, permission)
  cy.visit(url)
  cy.contains(textToSee)
})

// Assert that a page can not be visited
Cypress.Commands.add('canNotAccess', (url, permission) => {
  cy.changePermission(1, permission)
  cy.request({
    url: url,
    failOnStatusCode: false
  })
    .then((resp) => {
      expect(resp.status).to.eq(401)
    })
})

// Assert that an audit log has been created with the following content
// and redirect the page to the given url
Cypress.Commands.add('hasAuditLog', (content, redirectUrl) => {
  cy.visit('/1/account/audit')

  cy.contains(content)

  cy.visit(redirectUrl)
})

// Assert that an employee log has been created with the following content
// and redirect the page to the given url
Cypress.Commands.add('hasEmployeeLog', (content, redirectUrl) => {
  cy.visit('/1/employees/1/logs')

  cy.contains(content)

  cy.visit(redirectUrl)
})

// Change persmission of the user
Cypress.Commands.add('changePermission', (userId, permission) => {
  cy.exec('php artisan test:changepermission ' + userId + ' ' + permission)
})

// Assign an employee to a team
Cypress.Commands.add('assignEmployeeToTeam', (employeeId, teamId) => {
  cy.visit('/1/employees/' + employeeId)

  // Open the modal to assign a team and select the first line
  cy.get('[data-cy=open-team-modal-blank]').click()
  cy.get('[data-cy=list-team-' + teamId + ']').click()
  cy.get('.existing-teams').contains('product')
})
