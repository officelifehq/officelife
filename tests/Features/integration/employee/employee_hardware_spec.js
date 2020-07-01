describe('Employee - hardware', function () {
  it('should let an admin view an item from someone else', function () {
    cy.login()

    cy.createCompany()

    cy.createEmployee('Michael', 'Scott', 'michael.scott@dundermifflin.com', 'admin', true)

    cy.visit('/1/employees/2')
    cy.get('[data-cy=hardware-blank]').should('exist')

    cy.visit('/1/account')
    cy.get('[data-cy=hardware-admin-link]').click()

    // add one hardware and associate an employee
    cy.get('[data-cy=add-hardware-button]').click()
    cy.get('[data-cy=hardware-name-input]').type('Blackberry 18')
    cy.get('[data-cy=hardware-serial-input]').type('4565')
    cy.get('[data-cy=lend-hardware-checkbox]').check()
    cy.get('[data-cy=employee-selector]').click()
    cy.get('ul.vs__dropdown-menu>li').eq(1).click()
    cy.get('[data-cy=employee-selector]').click()
    cy.get('[data-cy=submit-add-hardware-button]').click()

    // go to the employee page
    cy.visit('/1/employees/2')
    cy.get('[data-cy=hardware-blank]').should('not.exist')
    cy.get('[data-cy=hardware-item-1]').should('contain', 'Blackberry 18')
  })

  it('should let an HR view an item from someone else', function () {
    cy.login()

    cy.createCompany()

    cy.createEmployee('Michael', 'Scott', 'michael.scott@dundermifflin.com', 'admin', true)

    cy.changePermission(1, 200)

    cy.visit('/1/employees/2')
    cy.get('[data-cy=hardware-blank]').should('exist')

    cy.visit('/1/account')
    cy.get('[data-cy=hardware-admin-link]').click()

    // add one hardware and associate an employee
    cy.get('[data-cy=add-hardware-button]').click()
    cy.get('[data-cy=hardware-name-input]').type('Blackberry 18')
    cy.get('[data-cy=hardware-serial-input]').type('4565')
    cy.get('[data-cy=lend-hardware-checkbox]').check()
    cy.get('[data-cy=employee-selector]').click()
    cy.get('ul.vs__dropdown-menu>li').eq(1).click()
    cy.get('[data-cy=employee-selector]').click()
    cy.get('[data-cy=submit-add-hardware-button]').click()

    // go to the employee page
    cy.visit('/1/employees/2')
    cy.get('[data-cy=hardware-blank]').should('not.exist')
    cy.get('[data-cy=hardware-item-1]').should('contain', 'Blackberry 18')
  })

  it('should let a normal user view his own hardware', function () {
    cy.login()

    cy.createCompany()

    cy.visit('/1/employees/1')
    cy.get('[data-cy=hardware-blank]').should('exist')

    cy.visit('/1/account')
    cy.get('[data-cy=hardware-admin-link]').click()

    // add one hardware and associate an employee
    cy.get('[data-cy=add-hardware-button]').click()
    cy.get('[data-cy=hardware-name-input]').type('Blackberry 18')
    cy.get('[data-cy=hardware-serial-input]').type('4565')
    cy.get('[data-cy=lend-hardware-checkbox]').check()
    cy.get('[data-cy=employee-selector]').click()
    cy.get('ul.vs__dropdown-menu>li').eq(0).click()
    cy.get('[data-cy=employee-selector]').click()
    cy.get('[data-cy=submit-add-hardware-button]').click()

    cy.changePermission(1, 300)

    // go to the employee page
    cy.visit('/1/employees/1')
    cy.get('[data-cy=hardware-blank]').should('not.exist')
    cy.get('[data-cy=hardware-item-1]').should('contain', 'Blackberry 18')
  })

  it('should not let a normal user view someone elses hardware', function () {
    cy.login()

    cy.createCompany()
    cy.createEmployee('Michael', 'Scott', 'michael.scott@dundermifflin.com', 'admin', true)

    cy.visit('/1/account')
    cy.get('[data-cy=hardware-admin-link]').click()

    // add one hardware and associate an employee
    cy.get('[data-cy=add-hardware-button]').click()
    cy.get('[data-cy=hardware-name-input]').type('Blackberry 18')
    cy.get('[data-cy=hardware-serial-input]').type('4565')
    cy.get('[data-cy=lend-hardware-checkbox]').check()
    cy.get('[data-cy=employee-selector]').click()
    cy.get('ul.vs__dropdown-menu>li').eq(1).click()
    cy.get('[data-cy=employee-selector]').click()
    cy.get('[data-cy=submit-add-hardware-button]').click()

    cy.changePermission(1, 300)

    cy.visit('/1/employees/2')
    cy.get('[data-cy=hardware-blank]').should('not.exist')
  })
})
