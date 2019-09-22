describe('Employee - assign address', function () {
  it('should let an admin edit an address', function () {
    cy.login()

    cy.createCompany()

    cy.createEmployee('Michael', 'Scott', 'michael.scott@dundermifflin.com', 'admin', true)
    cy.visit('/1/employees/2')
    cy.get('[data-cy=edit-profile-button]').click()
    cy.get('[data-cy=show-edit-view]').click()
    cy.url().should('include', '/2/edit')
    cy.get('body').should('contain', 'Edit information')

    cy.get('input[name=street]').type('612 St Jacques St')
    cy.get('input[name=city]').type('Montreal')
    cy.get('input[name=state]').type('Quebec')
    cy.get('input[name=postal_code]').type('H3C 4M8')
    cy.get('[data-cy=country_selector]').click()
    cy.get('ul.vs__dropdown-menu>li').eq(3).click()
    cy.get('input[name=state]').click()
    cy.get('[data-cy=submit-edit-employee-button]').click()
    cy.url().should('include', '/2')
  })

  it('should let a HR edit an address', function () {
    cy.login()

    cy.createCompany()
    cy.createEmployee('Michael', 'Scott', 'michael.scott@dundermifflin.com', 'admin', true)

    cy.changePermission(1, 200)
    cy.visit('/1/employees/2')
    cy.get('[data-cy=edit-profile-button]').click()
    cy.get('[data-cy=show-edit-view]').click()
    cy.url().should('include', '/2/edit')
    cy.get('body').should('contain', 'Edit information')

    cy.get('input[name=street]').type('612 St Jacques St')
    cy.get('input[name=city]').type('Montreal')
    cy.get('input[name=state]').type('Quebec')
    cy.get('input[name=postal_code]').type('H3C 4M8')
    cy.get('[data-cy=country_selector]').click()
    cy.get('ul.vs__dropdown-menu>li').eq(3).click()
    cy.get('input[name=state]').click()
    cy.get('[data-cy=submit-edit-employee-button]').click()
    cy.url().should('include', '/2')
  })

  it('should let a normal user edit his own address', function () {
    cy.login()

    cy.createCompany()
    cy.changePermission(1, 300)
    cy.visit('/1/employees/1')
    cy.get('[data-cy=edit-profile-button]').click()
    cy.get('[data-cy=show-edit-view]').click()
    cy.url().should('include', '/1/edit')
    cy.get('body').should('contain', 'Edit information')

    cy.get('input[name=street]').type('612 St Jacques St')
    cy.get('input[name=city]').type('Montreal')
    cy.get('input[name=state]').type('Quebec')
    cy.get('input[name=postal_code]').type('H3C 4M8')
    cy.get('[data-cy=country_selector]').click()
    cy.get('ul.vs__dropdown-menu>li').eq(3).click()
    cy.get('input[name=state]').click()
    cy.get('[data-cy=submit-edit-employee-button]').click()
    cy.url().should('include', '/1')
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
