describe('Employee - edit personal information', function () {
  it('should let an admin edit personal information', function () {
    cy.login()

    cy.createCompany()

    cy.createEmployee('Michael', 'Scott', 'michael.scott@dundermifflin.com', 'admin', true)
    cy.visit('/1/employees/2')
    cy.get('[data-cy=edit-profile-button]').click()
    cy.get('[data-cy=show-edit-view]').click()
    cy.url().should('include', '/2/edit')
    cy.get('body').should('contain', 'Edit information')

    cy.get('input[name=firstname]').type('dwight')
    cy.get('input[name=lastname]').type('schrute')
    cy.get('input[name=email]').clear()
    cy.get('input[name=email]').type('dwight@dundermifflin.com')
    cy.get('input[name=year]').type('1981')
    cy.get('input[name=month]').type('3')
    cy.get('input[name=day]').type('10')
    cy.get('[data-cy=submit-edit-employee-button]').click()
    cy.url().should('include', '/2')

    cy.hasAuditLog('Set the employee name and email address', '/1/employees/2')

    cy.visit('/1/employees/2/logs')
    cy.contains('Set the name and email to')
  })

  it('should let a HR edit an address', function () {
    cy.login()

    cy.createCompany()
    cy.createEmployee('Michael', 'Scott', 'michael.scott@dundermifflin.com', 'admin', true)

    cy.changePermission(1, 200)
    cy.visit('/1/employees/2')
    cy.get('[data-cy=edit-profile-button]').click()
    cy.get('[data-cy=show-edit-view]').click()

    cy.get('input[name=firstname]').type('dwight')
    cy.get('input[name=lastname]').type('schrute')
    cy.get('input[name=email]').clear()
    cy.get('input[name=email]').type('dwight@dundermifflin.com')
    cy.get('input[name=year]').type('1981')
    cy.get('input[name=month]').type('3')
    cy.get('input[name=day]').type('10')
    cy.get('[data-cy=submit-edit-employee-button]').click()
    cy.url().should('include', '/2')

    cy.visit('/1/employees/2/logs')
    cy.contains('Set the name and email to')

    cy.visit('/1/employees/2')
  })

  it('should let a normal user edit his own address', function () {
    cy.login()

    cy.createCompany()
    cy.changePermission(1, 300)
    cy.visit('/1/employees/1')
    cy.get('[data-cy=edit-profile-button]').click()
    cy.get('[data-cy=show-edit-view]').click()

    cy.get('input[name=firstname]').type('dwight')
    cy.get('input[name=lastname]').type('schrute')
    cy.get('input[name=email]').clear()
    cy.get('input[name=email]').type('dwight@dundermifflin.com')
    cy.get('input[name=year]').type('1981')
    cy.get('input[name=month]').type('3')
    cy.get('input[name=day]').type('10')
    cy.get('[data-cy=submit-edit-employee-button]').click()

    cy.visit('/1/employees/1/logs')
    cy.contains('Set the name and email to')
  })

  it('should not let a normal user edit someone elses address', function () {
    cy.login()

    cy.createCompany()
    cy.createEmployee('Michael', 'Scott', 'michael.scott@dundermifflin.com', 'admin', true)

    cy.changePermission(1, 300)

    cy.visit('/1/employees/2/edit')

    cy.url().should('include', '/home')
  })
})
